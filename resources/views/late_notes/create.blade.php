@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="mb-4 text-center">Form Keterlambatan</h4>

                        <form action="{{ route('keterlambatan.store') }}" method="POST">
                            @csrf

                            {{-- Waktu Keterlambatan --}}
                            <div class="mb-3">
                                <label for="tanggal_terlambat" class="form-label">Waktu Keterlambatan</label>
                                <input type="date" class="form-control @error('tanggal_terlambat') is-invalid @enderror"
                                    id="tanggal_terlambat" name="tanggal_terlambat" value="{{ old('tanggal_terlambat') }}"
                                    required>
                                @error('tanggal_terlambat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alasan --}}
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan</label>
                                <textarea id="alasan" class="form-control @error('alasan') is-invalid @enderror" name="alasan" rows="3"
                                    required placeholder="Tulis alasan keterlambatan...">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kamera + Preview Foto --}}
                            <div class="mb-3 text-center">
                                <label class="form-label d-block">Ambil Foto dari Kamera</label>
                                <video id="video" class="rounded shadow-sm mb-2" width="100%" autoplay
                                    playsinline></video>
                                <button type="button" id="switchCamera" class="btn btn-sm btn-secondary mb-3">Ganti
                                    Kamera</button>

                                <button type="button" id="capture" class="btn btn-outline-primary btn-sm mb-3">Ambil
                                    Foto</button>

                                <canvas id="canvas" class="d-none"></canvas>
                                <input type="hidden" name="foto" id="fotoInput" required>

                                <div>
                                    <strong class="d-block mb-2">Preview Foto</strong>
                                    <img id="preview" src="" class="img-thumbnail shadow-sm"
                                        style="max-width: 100%; display: none;">
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Kamera --}}
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capture = document.getElementById('capture');
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview');
        const switchCamera = document.getElementById('switchCamera');

        let useFrontCamera = false;
        let currentStream = null;

        async function startCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                video: {
                    facingMode: useFrontCamera ? 'user' : {
                        exact: 'environment'
                    }
                }
            };

            try {
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;
            } catch (err) {
                alert("Tidak bisa mengakses kamera: " + err.message);
            }
        }

        // Tombol switch kamera
        switchCamera.addEventListener('click', () => {
            useFrontCamera = !useFrontCamera;
            startCamera();
        });

        // Ambil foto
        capture.addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0);
            const dataURL = canvas.toDataURL('image/jpeg');
            fotoInput.value = dataURL;
            preview.src = dataURL;
            preview.style.display = 'block';
        });

        // Jalankan kamera pertama kali
        startCamera();
    </script>
@endsection
