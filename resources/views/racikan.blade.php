@extends('layout.layout')

@section('title','Racikan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Racikan</h4>
        </div>
        <div class="card-body">
            <form id="formRacik">
                <div class="row">
                    @csrf
                    <div class="col-12 cont-sel">
                        <div class="row">
                            <div class="col-7">
                                {{-- <label for="obat" class="form-label">Obat</label>
                                    <input class="form-control" name="obat[]" list="datalistobat" id="obat" placeholder="Type to search...">
                                <datalist id="datalistobat">
                                    @foreach ($obats as $obat)
                                <option value="{{$obat->obatalkes_kode}}">{{ $obat->obatalkes_nama }} | stok: {{$obat->stok}}</option>
                            @endforeach
                                </datalist> --}}
                                <div class="form-group">
                                    <label for="obat">Obat</label>
                                    <select class="selectpicker form-control" name="obat[]" id="obat" data-live-search="true" title="Pilih Obat" required>
                                        @foreach ($obats as $obat)
                                    @if ($obat->stok == 0)
                                        <option value="{{$obat->obatalkes_kode}}" disabled>{{ $obat->obatalkes_nama }} | stok: {{$obat->stok}}</option>
                                    @else
                                        <option value="{{$obat->obatalkes_kode}}">{{ $obat->obatalkes_nama }} | stok: {{$obat->stok}}</option>
                                    @endif
                                    @endforeach
                                    </select>
                                  </div>
                            </div>
                            <div class="col-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="qty[]" id="qty" placeholder="Quantity" required>
                            </div>
                            <div class="col-2 d-flex align-items-center mt-3">
                                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-add">Add</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-12 mb-3">
                        <label for="signa" class="form-label">Signa</label>
                            <input class="form-control" name="signa" list="datalistsigna" id="signa" placeholder="Type to search...">
                        <datalist id="datalistsigna">
                            @foreach ($signas as $signa)
                                <option value="{{ $signa->signa_kode }}">{{ $signa->signa_nama }}</option>
                            @endforeach
                        </datalist>
                    </div> --}}
                    <div class="col-12">
                        <div class="form-group">
                            <label for="signa">Signa</label>
                            <select class="selectpicker form-control" name="signa" id="signa" data-live-search="true" title="Pilih Signa" required>
                                @foreach ($signas as $signa)
                                <option value="{{ $signa->signa_kode }}">{{ $signa->signa_nama }}</option>
                            @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="rck_nama" class="form-label">Nama Racikan</label>
                        <input type="text" class="form-control" name="rck_nama" id="rck_nama" placeholder="Nama racikan" required>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                Draft Resep
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Racikan</th>
                                                    <th>Signa</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bodyTableN">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Obat</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bodyTableO">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.selectpicker').selectpicker();
        let listNum = 1;
        let listObat = ()=>{
            let data;
            $.ajax({
                type: "GET",
                url: "/racikan/show",
                dataType: "JSON",
                async:false,
                success: function (response) {
                    let optionObat='';
                    $.each(response, function (i, val) { 
                        optionObat += `<option value="${val.obatalkes_kode}">${val.obatalkes_nama} | stok: ${val.stok}</option>`;
                    });
                    data = optionObat;
                }
            });
            return data;
        }
        $('.btn-add').click(function () {
            $('.cont-sel').append(`<div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <label for="obat-${listNum}">Obat</label>
                                    <select class="selectpicker form-control" name="obat[]" id="obat-${listNum}" data-live-search="true" title="Pilih Obat" required>
                                        ${listObat()}
                                    </select>
                                  </div>
                            </div>
                            <div class="col-3">
                                <label for="qty-${listNum}" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="qty[]" id="qty-${listNum}" placeholder="Quantity" required>
                            </div>
                            <div class="col-2 d-flex align-items-center mt-3">
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="remove()">Remove</a>
                            </div>
                        </div>`)
            listNum += 1;
            $('.selectpicker').selectpicker();
        })
        let remove = ()=>{
            $('.cont-sel .row').last().remove();
            if(listNum != 1){
                listNum -= 1;
            }
        }
        $(document).click(function(){
            $('.bodyTableO').html('');
            $('.bodyTableN').html('');
            let arrObat = $('[name="obat[]"]').map(function(){
                return $(this)[0].options[$(this)[0].selectedIndex].text;
            }).get()
            let arrQty = $('[name="qty[]"]').map(function(){
                return $(this).val()
            }).get();
            for(let i=0;i<arrObat.length;i++){
                if(arrObat[i] != ''){
                    $('.bodyTableO').append(`<tr>
                                    <td>${arrObat[i]}</td>
                                    <td>${arrQty[i]}</td>
                                </tr>`);
                }
            }
            if($('#rck_nama').val() != '' || $('#signa').val() != ''){
                $('.bodyTableN').html(`<tr>
                                        <td>${$('#rck_nama').val()}</td>
                                        <td>${$('#signa')[0].options[$('#signa')[0].selectedIndex].text}</td>
                                    </tr>`);
            }
        })
        $('#rck_nama').keyup(function(){
            $('.bodyTableN').html(`<tr>
                                        <td>${$('#rck_nama').val()}</td>
                                        <td>${$('#signa')[0].options[$('#signa')[0].selectedIndex].text}</td>
                                    </tr>`);
        })
        $('#formRacik').submit(function (e) { 
            e.preventDefault();
            let data = new FormData(this);
            $.ajax({
                url: "racikan",
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function (response) {
                    if(response.true == false){
                        Swal.fire(
                        'Gagal',
                        response.message,
                        'error'
                        );
                    }else{
                        Swal.fire(
                        'Berhasil',
                        response.message,
                        'success'
                        ).then((result)=>{
                            location.reload();
                        })
                    }
                }
            });
        })
    </script>
@endsection