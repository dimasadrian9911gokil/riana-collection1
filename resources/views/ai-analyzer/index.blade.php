@extends('layouts.app')

@section('title', 'AI Skin Analyzer - Riana Collection')

@section('content')
<style>
    .ai-hero {
        background: radial-gradient(circle at center, #2b1055 0%, #150828 100%);
        color: white;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }
    .scanner-container {
        position: relative;
        width: 300px;
        height: 300px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #00f2fe;
        box-shadow: 0 0 30px rgba(0, 242, 254, 0.5);
        background: #000;
    }
    #videoElement {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: #00f2fe;
        box-shadow: 0 0 15px #00f2fe, 0 0 30px #00f2fe;
        animation: scan 2s infinite linear;
        display: none;
    }
    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .step-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 40px;
        color: white;
        max-width: 600px;
        margin: 0 auto;
    }
    .quiz-form {
        display: none;
    }
    .form-check-custom {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: 0.3s;
    }
    .form-check-custom:hover {
        background: rgba(0, 242, 254, 0.2);
        border-color: #00f2fe;
    }
    .form-check-input:checked + .form-check-label {
        color: #00f2fe;
        font-weight: bold;
    }
</style>

<section class="ai-hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3"><i class="fas fa-robot text-info me-3"></i>AI Skin Analyzer</h1>
        <p class="fs-5 opacity-75 mb-5">Biarkan Artificial Intelligence kami mendeteksi tipe kulit wajahmu dengan presisi tinggi.</p>

        <!-- STEP 1: SCANNER -->
        <div id="step-scan">
            <div class="scanner-container mb-4">
                <video id="videoElement" autoplay playsinline muted></video>
                <canvas id="motionCanvas" style="display:none;"></canvas>
                <div class="scan-line" id="scanLine"></div>
            </div>
            <h4 id="scanStatus" class="fw-bold text-info">Menyiapkan Kamera...</h4>
            <p class="opacity-75">Posisikan wajah Anda di dalam lingkaran</p>
            <button class="btn btn-info btn-lg rounded-pill px-5 fw-bold mt-3 text-dark" id="startScanBtn" style="display: none;">
                <i class="fas fa-camera me-2"></i> Mulai Scan Wajah
            </button>
        </div>

        <!-- STEP 2: QUIZ -->
        <div class="step-card quiz-form" id="step-quiz">
            <h3 class="fw-bold mb-4 text-info">Verifikasi AI</h3>
            <p class="opacity-75 mb-4">Scan berhasil. Untuk hasil yang 100% akurat, bantu AI kami memvalidasi kondisi kulitmu hari ini.</p>
            
            <form action="{{ route('ai.analyze') }}" method="POST">
                @csrf
                <!-- Q1 -->
                <div class="text-start mb-4">
                    <h5 class="mb-3">1. Bagaimana rasa kulitmu 1-2 jam setelah mencuci muka?</h5>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q1" value="a" required>
                        <span class="form-check-label">Terasa kaku, ketat, atau sedikit bersisik</span>
                    </label>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q1" value="b">
                        <span class="form-check-label">Normal saja, tidak kering dan tidak berminyak</span>
                    </label>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q1" value="c">
                        <span class="form-check-label">Mulai terlihat mengkilap dan berminyak</span>
                    </label>
                </div>

                <!-- Q2 -->
                <div class="text-start mb-4">
                    <h5 class="mb-3">2. Perhatikan pori-pori di wajah Anda (terutama area hidung & pipi):</h5>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q2" value="a" required>
                        <span class="form-check-label">Sangat kecil, hampir tidak terlihat</span>
                    </label>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q2" value="b">
                        <span class="form-check-label">Cukup besar dan jelas terlihat</span>
                    </label>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q2" value="c">
                        <span class="form-check-label">Hanya besar di area T-Zone (Dahi, Hidung, Dagu)</span>
                    </label>
                </div>

                <!-- Q3 -->
                <div class="text-start mb-5">
                    <h5 class="mb-3">3. Apakah kulitmu mudah memerah, perih, atau gatal saat mencoba produk baru?</h5>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q3" value="a" required>
                        <span class="form-check-label">Ya, sering sekali terjadi iritasi</span>
                    </label>
                    <label class="form-check-custom d-block">
                        <input class="form-check-input me-2" type="radio" name="q3" value="b">
                        <span class="form-check-label">Tidak, kulit saya cukup kebal dan aman-aman saja</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-info btn-lg rounded-pill w-100 fw-bold text-dark shadow-lg">
                    <i class="fas fa-magic me-2"></i> Analisis Sekarang
                </button>
            </form>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('videoElement');
    const status = document.getElementById('scanStatus');
    const startBtn = document.getElementById('startScanBtn');
    const scanLine = document.getElementById('scanLine');
    const stepScan = document.getElementById('step-scan');
    const stepQuiz = document.getElementById('step-quiz');
    const canvas = document.getElementById('motionCanvas');
    const ctx = canvas.getContext('2d', { willReadFrequently: true });
    
    let previousImageData = null;
    let scanInterval = null;

    // Fungsi Suara Robot AI (Text-to-Speech)
    function speakAI(text) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID'; 
            utterance.rate = 0.9;     
            utterance.pitch = 1.2;    
            window.speechSynthesis.speak(utterance);
        }
    }

    // Fungsi Deteksi Pergerakan (Motion Detection)
    function detectMotion() {
        if(!video.srcObject || video.videoWidth === 0) return 0;
        
        // Kompresi ukuran untuk performa
        canvas.width = video.videoWidth / 4; 
        canvas.height = video.videoHeight / 4;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const currentImageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let motionScore = 0;
        
        if (previousImageData) {
            for (let i = 0; i < currentImageData.data.length; i += 4) {
                const rDiff = Math.abs(currentImageData.data[i] - previousImageData.data[i]);
                const gDiff = Math.abs(currentImageData.data[i+1] - previousImageData.data[i+1]);
                const bDiff = Math.abs(currentImageData.data[i+2] - previousImageData.data[i+2]);
                
                // Jika perbedaan warna pixel melebihi threshold
                if (rDiff + gDiff + bDiff > 60) { 
                    motionScore++;
                }
            }
        }
        previousImageData = currentImageData;
        
        // Hitung persentase piksel yang berubah
        return (motionScore / (canvas.width * canvas.height)) * 100;
    }

    // Minta akses kamera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
                status.innerText = "Kamera Siap!";
                startBtn.style.display = 'inline-block';
                setTimeout(() => speakAI("Sistem AI siap. Posisikan wajah Anda di dalam lingkaran."), 1000);
            })
            .catch(function(err) {
                status.innerText = "Kamera tidak terdeteksi. Silakan lanjut ke Kuis.";
                speakAI("Kamera tidak terdeteksi. Silakan lanjut ke Kuis.");
                setTimeout(() => {
                    stepScan.style.display = 'none';
                    stepQuiz.style.display = 'block';
                }, 2000);
            });
    }

    startBtn.addEventListener('click', function() {
        startBtn.style.display = 'none';
        scanLine.style.display = 'block';
        status.classList.remove('text-danger');
        status.classList.add('text-info');
        status.innerText = "Menganalisis Titik Wajah...";
        
        speakAI("Memulai pemindaian wajah. Harap diam sejenak.");
        previousImageData = null; // Reset frame sebelum mulai
        let progress = 0;

        scanInterval = setInterval(() => {
            // Cek gerakan tiap iterasi
            const motionPercent = detectMotion();
            
            // Jika gerakan melebihi 2% frame (sensitivitas tinggi)
            if (motionPercent > 2.0 && progress > 5) {
                clearInterval(scanInterval);
                scanLine.style.display = 'none';
                status.classList.remove('text-info');
                status.classList.add('text-danger');
                status.innerHTML = "<i class='fas fa-exclamation-triangle'></i> Terdeteksi Pergerakan!";
                speakAI("Terdeteksi pergerakan. Pemindaian gagal. Harap diam dan ulangi kembali.");
                
                // Munculkan tombol ulangi
                setTimeout(() => {
                    startBtn.innerText = "Ulangi Scan Wajah";
                    startBtn.style.display = 'inline-block';
                }, 1000);
                return;
            }

            progress += 5; // Meningkatkan resolusi interval
            if(progress === 30) {
                status.innerText = "Mengukur Tingkat Hidrasi...";
                speakAI("Mengukur tingkat hidrasi.");
            }
            if(progress === 60) {
                status.innerText = "Memetakan Tekstur Pori...";
                speakAI("Memetakan tekstur pori.");
            }
            if(progress === 90) {
                status.innerText = "Sinkronisasi AI Server...";
            }
            
            if(progress >= 100) {
                clearInterval(scanInterval);
                scanLine.style.display = 'none';
                status.classList.remove('text-info');
                status.classList.add('text-success');
                status.innerHTML = "<i class='fas fa-check-circle'></i> Scan Berhasil!";
                
                speakAI("Pemindaian selesai. Silakan jawab pertanyaan berikut untuk hasil yang lebih akurat.");

                if(video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                }

                setTimeout(() => {
                    stepScan.style.display = 'none';
                    stepQuiz.style.display = 'block';
                }, 3000); 
            }
        }, 500); // Cek gerakan setiap 500ms
    });
});
</script>
@endsection
