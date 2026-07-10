@extends('layouts.app')
@section('title', 'Daftar Organisasi')

@section('content')
<div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:2rem 1rem">
    <div style="width:100%;max-width:520px">
        <div style="text-align:center;margin-bottom:2rem">
            <h1 style="font-size:1.6rem;font-weight:800;color:#1e293b"> Daftar Organisasi / Syarikat</h1>
            <p style="color:#64748b;font-size:.9rem;margin-top:.3rem">Cipta dan uruskan kempen kebajikan anda melalui MyInfaq</p>
        </div>
        <div class="card" style="padding:2rem">
            <form method="POST" action="{{ route('register.organisasi.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="nama_organisasi">Nama Organisasi / Syarikat</label>
                    <input type="text" id="nama_organisasi" name="nama_organisasi" class="form-control" value="{{ old('nama_organisasi') }}" required>
                    @error('nama_organisasi')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group">
                        <label class="form-label" for="no_pendaftaran">No. Pendaftaran ROB/ROC</label>
                        <input type="text" id="no_pendaftaran" name="no_pendaftaran" class="form-control" value="{{ old('no_pendaftaran') }}" required placeholder="SA0001234-X">
                        @error('no_pendaftaran')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="no_telefon">No. Telefon</label>
                        <input type="text" id="no_telefon" name="no_telefon" class="form-control" value="{{ old('no_telefon') }}" required placeholder="03-xxxxxxxx">
                        @error('no_telefon')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat (Pilihan)</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">E-mel Organisasi</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Kata Laluan</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="8">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Sahkan Kata Laluan</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>
                <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:.625rem;padding:.85rem 1rem;margin-bottom:1.25rem;font-size:.83rem;color:#92400e">
                     Kempen yang anda cipta akan melalui proses semakan oleh staf kami sebelum diaktifkan.
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.8rem;font-size:1rem">Daftar Organisasi</button>
            </form>
        </div>
        <div style="text-align:center;margin-top:1rem;font-size:.88rem;color:#64748b">
            Sudah ada akaun? <a href="{{ route('login') }}" style="color:#0d9488;font-weight:600">Log Masuk</a>
            
        </div>
    </div>
</div>
@endsection
