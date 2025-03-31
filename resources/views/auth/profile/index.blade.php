@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Profile</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update', ['profile' => $user->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="profile_image">Foto Profil:</label>
                                <img src="{{ asset('profile_images/' . auth()->user()->profile_image) }}" id="current_image"
                                    alt="Profil Gambar" class="rounded-circle" style="max-width: 200px;">
                                <img src="" id="image_preview" alt="Pratinjau Gambar"
                                    style="display: none; max-width: 200px;" class="rounded-circle mb-3">
                                <input class="form-control @error('name') is-invalid @enderror" type="file"
                                    name="profile_image" id="profile_image">
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select id="gender" name="gender"
                                    class="form-control @error('gender') is-invalid @enderror">
                                    <option value="">Pilih Gender</option>
                                    <option value="Laki-laki"
                                        {{ old('gender', auth()->user()->gender) == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('gender', auth()->user()->gender) == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="marital_status">Jenis Kelamin</label>
                                <select id="marital_status" name="marital_status"
                                    class="form-control @error('marital_status') is-invalid @enderror">
                                    <option value="">Pilih Perkawinan</option>
                                    <option value="Belum Kawin"
                                        {{ old('marital_status', auth()->user()->marital_status) == 'Belum Kawin' ? 'selected' : '' }}>
                                        Belum Kawin</option>
                                    <option value="Kawin"
                                        {{ old('marital_status', auth()->user()->marital_status) == 'Kawin' ? 'selected' : '' }}>
                                        Kawin</option>
                                </select>
                                @error('marital_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="birth_date">Tanggal Lahir</label>
                                <input id="birth_date" type="date"
                                    class="form-control @error('birth_date') is-invalid @enderror" name="birth_date"
                                    value="{{ old('birth_date', auth()->user()->birth_date ? auth()->user()->birth_date->format('Y-m-d') : '') }}">
                                @error('birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Fungsi ini akan dipanggil setiap kali gambar yang dipilih berubah
        function previewImage() {
            const input = document.getElementById('profile_image');
            const preview = document.getElementById('image_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.setAttribute('src', e.target.result);
                    preview.style.display = 'block'; // Menampilkan pratinjau gambar
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none'; // Sembunyikan pratinjau jika tidak ada gambar yang dipilih
            }
        }

        // Mengikat fungsi pratinjau ke perubahan input gambar
        const fileInput = document.getElementById('profile_image');
        fileInput.addEventListener('change', previewImage);
    </script>
@endsection
