@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-primary mb-2" href="/products">Kembali</a>
    <div class="card">
        <div class="card-body">
            <h4 class="my-auto">Master Produk</h4>
            <hr>
            <form method="POST" action="/products/{{ $product->id }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="name">Nama*</label>
                    <input id="name" class="form-control" type="text" name="name" value="{{ old('name') ?? $product->name }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="image">Gambar</label>
                    <div class="custom-file">
                        <input id="image" class="custom-file-input" type="file" accept="image/png, image/jpeg" name="image" value="{{ old('image') }}">
                        <label id="image-label" class="custom-file-label" for="image">Pilih gambar</label>
                    </div>
                    <span>Kosongi apabila tidak ingin mengupdate gambar yang sudah ada</span>
                    @error('image')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="price">Harga*</label>
                    <input id="price" class="form-control" type="text" name="price" value="{{ old('price') ?? $product->price }}">
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="quantity">Stock*</label>
                    <input id="quantity" class="form-control" type="text" name="quantity" value="{{ old('quantity') ?? $product->quantity }}">
                    @error('quantity')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <hr>
                <div class="d-flex">
                    <button class="btn btn-primary ml-auto" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('image').addEventListener('change', (e) => {
        const fullPath = document.getElementById('image').value

        if (fullPath) {
            const startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'))
            let filename = fullPath.substring(startIndex)

            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1)
            }

            document.getElementById('image-label').innerHTML = filename
        }
    })
</script>
@endsection