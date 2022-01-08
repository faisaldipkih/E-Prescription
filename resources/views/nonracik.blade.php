@extends('layout.layout')

@section('title','Non Racikan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Non Racikan</h4>
        </div>
        <div class="card-body">
            <form id="formNon">
                <div class="row">
                    @csrf
                    {{-- <div class="col-12 mb-3">
                        <label for="obat" class="form-label">Obat</label>
                            <input class="form-control" name="obat" list="datalistobat" id="obat" placeholder="Type to search...">
                        <datalist id="datalistobat">
                            @foreach ($obats as $obat)
                            @if ($obat->stok == 25.00)
                                <option value="{{$obat->obatalkes_kode}}">{{ $obat->obatalkes_nama }} | stok: {{$obat->stok}}</option>
                            @else
                                <option value="{{$obat->obatalkes_kode}}">{{ $obat->obatalkes_nama }} | stok: {{$obat->stok}}</option>
                            @endif
                            @endforeach
                        </datalist>
                    </div> --}}
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
                            <label for="obat">Obat</label>
                            <select class="selectpicker form-control" name="obat" id="obat" data-live-search="true" title="Pilih Obat" required>
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
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="qty" id="qty" placeholder="quantity" required>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                Draft Resep
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Obat</th>
                                            <th>Signa</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bodyTable">
                                    </tbody>
                                </table>
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
        $(document).click(function(){
            $('.bodyTable').html('')
            if($('#obat').val()!=''||$('#signa').val()!=''||$('#qty').val()!=''){
                $('.bodyTable').html(`<tr>
                                        <td>${$('#obat')[0].options[$('#obat')[0].selectedIndex].text}</td>
                                        <td>${$('#signa')[0].options[$('#signa')[0].selectedIndex].text}</td>
                                        <td>${$('#qty').val()}</td>
                                    </tr>`)
                $('#qty').keyup(function(){
                    $('.bodyTable').html(`<tr>
                                        <td>${$('#obat')[0].options[$('#obat')[0].selectedIndex].text}</td>
                                        <td>${$('#signa')[0].options[$('#signa')[0].selectedIndex].text}</td>
                                        <td>${$('#qty').val()}</td>
                                    </tr>`)
                })
            }
        })
        $('#formNon').submit(function (e) { 
            e.preventDefault();
            let data = new FormData(this);
            $.ajax({
                url: "non-racikan/store",
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