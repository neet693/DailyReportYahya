@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="mb-4 text-center">Form Keterlambatan</h4>

                        <form action="{{ route('keterlambatan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Waktu Keterlambatan --}}
                            <div class="mb-3">
                                <label for="tanggal_terlambat" class="form-label">Waktu Keterlambatan</label>
                                <input type="datetime-local"
                                    class="form-control @error('tanggal_terlambat') is-invalid @enderror"
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

                                {{-- Live Camera --}}
                                <video id="video" class="rounded shadow-sm mb-2" width="100%" autoplay playsinline
                                    muted></video>
                                <button type="button" id="switchCamera" class="btn btn-sm btn-secondary mb-3">Ganti
                                    Kamera</button>
                                <button type="button" id="capture" class="btn btn-outline-primary btn-sm mb-3">Ambil
                                    Foto</button>

                                {{-- Canvas untuk capture --}}
                                <canvas id="canvas" class="d-none"></canvas>
                                <input type="hidden" name="foto" id="fotoInput">

                                {{-- Fallback Input File (untuk iOS jika kamera gagal) --}}
                                {{-- <input type="file" accept="image/*" capture="environment" id="fallbackInput" --}}
                                <input type="file" accept="image/*" id="fallbackInput" name="foto_fallback"
                                    class="form-control d-none mt-2">

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
        const fallbackInput = document.getElementById('fallbackInput');

        let useFrontCamera = false;
        let currentStream = null;

        async function startCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
            }

            const constraints = {
                video: {
                    facingMode: useFrontCamera ? "user" : {
                        ideal: "environment"
                    }
                },
                audio: false
            };

            try {
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;
            } catch (err) {
                console.warn("Kamera gagal, fallback ke input file:", err.message);
                // Matikan video, tampilkan fallback input
                video.style.display = 'none';
                switchCamera.style.display = 'none';
                capture.style.display = 'none';
                fallbackInput.classList.remove('d-none');
            }
        }

        // Tombol switch kamera
        switchCamera.addEventListener('click', () => {
            useFrontCamera = !useFrontCamera;
            startCamera();
        });

        // Ambil foto dari kamera
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

        // Preview fallback input file
        fallbackInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Jalankan kamera pertama kali
        startCamera();
    </script>
@endsection
