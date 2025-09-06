<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحلیل تصویر گیاه</title>
    <link href="https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#F6F1E1',
                        header: '#F3A6B5',
                        primary: '#A8D5BA',
                        danger: '#F15B5B',
                        secondary: '#6DB7F2'
                    },
                    fontFamily: {
                        'vazir': ['Vazir', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Vazir', sans-serif;
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #A8D5BA 0%, #6DB7F2 100%);
        }
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Animation classes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Table styles */
        .results-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .results-table th {
            background-color: #F3A6B5;
            color: white;
            font-weight: 600;
            padding: 14px 16px;
            text-align: right;
            font-size: 15px;
        }
        
        .results-table tr:nth-child(odd) td {
            background-color: white;
        }
        
        .results-table tr:nth-child(even) td {
            background-color: #FDFBF6;
        }
        
        .results-table td {
            padding: 14px 16px;
            text-align: right;
            vertical-align: top;
            line-height: 1.6;
        }
        
        .results-table tr:hover td {
            background-color: #f8f8f8;
        }
        
        /* Fertilizer row special styling */
        .fertilizer-row td {
            background-color: #f1f9f3 !important;
            border-top: 1px solid #A8D5BA;
            border-bottom: 1px solid #A8D5BA;
        }
        
        .fertilizer-row:hover td {
            background-color: #e5f2e9 !important;
        }
        
        /* Copy button styles */
        .copy-btn {
            color: #6DB7F2;
            transition: all 0.2s;
            opacity: 0.9;
        }
        
        .copy-btn:hover {
            color: #5AA0E0;
            transform: scale(1.1);
            opacity: 1;
        }
        
        /* Results page specific styles */
        .results-header {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .results-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to left, #F3A6B5, transparent);
            border-radius: 3px;
        }
        
        /* PDF button icon styling */
        .pdf-icon {
            margin-left: 8px;
        }
    </style>
</head>
<body class="bg-background min-h-screen flex items-center justify-center p-4 font-vazir">

<!-- Page 1: Main Selection -->
<div id="page-1" class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden">
    <div class="p-8">
        <h2 class="text-3xl font-bold text-header mb-8 text-center">آنالیز هوشمند گیاه</h2>
        <p class="text-gray-600 mb-8 text-center">لطفاً روش مورد نظر برای ارسال تصویر گیاه خود را انتخاب کنید</p>
        
        <div class="space-y-4">
            <button id="camera-button" class="w-full bg-primary hover:bg-primary/90 text-white py-4 px-6 rounded-xl flex items-center justify-center space-x-reverse space-x-3 transition-all duration-300 btn-hover-effect">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>عکس گرفتن با دوربین</span>
            </button>
            
            <button id="upload-button" class="w-full bg-secondary hover:bg-secondary/90 text-white py-4 px-6 rounded-xl flex items-center justify-center space-x-reverse space-x-3 transition-all duration-300 btn-hover-effect">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                <span>بارگذاری از گالری</span>
            </button>
        </div>
    </div>
    
    <div class="py-4 px-8 text-sm text-center text-gray-500">
        برای دریافت بهترین نتیجه، تصویری واضح با نور کافی تهیه کنید
    </div>
</div>

<!-- Page 2: File Upload -->
<div id="page-2" class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden hidden">
    <div class="p-8">
        <h2 class="text-3xl font-bold text-header mb-8 text-center">بارگذاری تصویر</h2>
        
        <div class="mb-8">
            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                <input type="file" id="file-input" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-sm text-gray-500 mb-1">فایل خود را بکشید و اینجا رها کنید، یا کلیک کنید</p>
                <p id="file-name" class="text-sm font-medium text-secondary mt-2">هیچ فایلی انتخاب نشده است</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <button id="file-confirm-button" class="w-full bg-primary hover:bg-primary/90 text-white py-4 px-6 rounded-xl transition-all duration-300 btn-hover-effect" disabled>
                ارسال برای تحلیل
            </button>
            
            <button id="back-to-main-2" class="w-full bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-6 rounded-xl transition-all duration-300">
                بازگشت
            </button>
        </div>
    </div>
</div>

<!-- Page 3: Camera Capture -->
<div id="page-3" class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden hidden">
    <div class="p-8">
        <h2 class="text-3xl font-bold text-header mb-6 text-center">گرفتن عکس</h2>
        
        <div class="relative mb-6 overflow-hidden rounded-xl bg-black">
            <video id="camera-stream" autoplay class="w-full h-72 object-cover"></video>
            <div id="camera-loading" class="absolute inset-0 flex items-center justify-center bg-black/80 text-white">
                <svg class="animate-spin h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        
        <div class="space-y-4">
            <button id="capture-button" class="w-full gradient-bg text-white py-4 px-6 rounded-xl transition-all duration-300 btn-hover-effect flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                عکس بگیر
            </button>
            
            <button id="back-to-main-3" class="w-full bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-6 rounded-xl transition-all duration-300">
                بازگشت
            </button>
        </div>
    </div>
</div>

<!-- Page 4: Image Preview -->
<div id="page-4" class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden hidden">
    <div class="p-8">
        <h2 class="text-3xl font-bold text-header mb-6 text-center">تصویر شما</h2>
        
        <div class="mb-6 rounded-xl overflow-hidden bg-gray-100">
            <img id="output-image" alt="تصویر انتخاب شده" class="w-full h-72 object-contain">
        </div>
        
        <div class="space-y-3">
            <button id="image-confirm-button" class="w-full bg-primary hover:bg-primary/90 text-white py-4 px-6 rounded-xl transition-all duration-300 btn-hover-effect">
                ارسال برای تحلیل
            </button>
            
            <button id="retake-button" class="w-full bg-secondary hover:bg-secondary/90 text-white py-3 px-6 rounded-xl transition-all duration-300">
                گرفتن عکس جدید
            </button>
            
            <button id="back-to-main-4" class="w-full bg-transparent border border-gray-300 text-gray-700 hover:bg-gray-50 py-3 px-6 rounded-xl transition-all duration-300">
                بازگشت
            </button>
        </div>
    </div>
</div>

<!-- Page 5: Analysis Results -->
<div id="page-5" class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden hidden">
    <div class="p-8">
        <h2 class="text-3xl font-bold text-header mb-2 text-center results-header">نتیجه تحلیل گیاه</h2>
        
        <div class="mb-6 overflow-hidden rounded-xl">
            <table id="results-table" class="results-table">
                <thead>
                    <tr>
                        <th scope="col" class="text-right">ویژگی</th>
                        <th scope="col" class="text-right">نتیجه</th>
                    </tr>
                </thead>
                <tbody id="analysis-table">
                    <!-- Table rows will be dynamically generated -->
                </tbody>
            </table>
        </div>
        
        <div class="flex space-x-reverse space-x-4 mb-4">
            <button id="buy-button" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-xl transition-all duration-300">خرید</button>
            <button id="contact-button" onclick="window.location.href='https://example.com/contact'" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-3 px-4 rounded-xl transition-all duration-300">ارتباط</button>

            <button id="download-pdf" class="flex-1 bg-primary hover:bg-primary/90 text-white py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 pdf-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                دانلود PDF
            </button>
            <button id="back-to-main-5" class="flex-1 bg-secondary hover:bg-secondary/90 text-white py-3 px-4 rounded-xl transition-all duration-300">
                بازگشت
            </button>
        </div>
    </div>
</div>

<!-- Notification Component -->
<div id="notification" class="fixed top-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-lg text-white z-50 opacity-0 transition-opacity duration-300 hidden"></div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-8 rounded-2xl flex flex-col items-center max-w-sm w-full">
        <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-gray-800 font-medium">در حال تحلیل تصویر شما...</p>
        <p class="text-gray-500 text-sm mt-2">لطفاً شکیبا باشید</p>
    </div>
</div>

<script>
    // DOM Elements
    const pages = {
        page1: document.getElementById("page-1"),
        page2: document.getElementById("page-2"),
        page3: document.getElementById("page-3"),
        page4: document.getElementById("page-4"),
        page5: document.getElementById("page-5"),
    };

    // Camera elements
    const cameraStream = document.getElementById("camera-stream");
    const cameraLoading = document.getElementById("camera-loading");
    const captureButton = document.getElementById("capture-button");
    
    // File elements
    const fileInput = document.getElementById("file-input");
    const fileName = document.getElementById("file-name");
    const fileConfirmButton = document.getElementById("file-confirm-button");
    
    // Image preview elements
    const outputImage = document.getElementById("output-image");
    
    // Loading and notification elements
    const loadingOverlay = document.getElementById("loading-overlay");
    const notification = document.getElementById("notification");
    
    // Button elements
    const backButtons = {
        page2: document.getElementById("back-to-main-2"),
        page3: document.getElementById("back-to-main-3"),
        page4: document.getElementById("back-to-main-4"),
        page5: document.getElementById("back-to-main-5"),
    };
    
    // Global variables
    let currentStream;
    
    // Show the first page
    openPage(pages.page1);

    // Event listeners for main page
    document.getElementById("camera-button").addEventListener("click", openCamera);
    document.getElementById("upload-button").addEventListener("click", () => openPage(pages.page2));
    
    // Back button handlers
    backButtons.page2.addEventListener("click", () => openPage(pages.page1));
    backButtons.page3.addEventListener("click", handleBackToMainFromCamera);
    backButtons.page4.addEventListener("click", handleBackToMainFromPreview);
    backButtons.page5.addEventListener("click", () => openPage(pages.page1));
    
    // File input handling
    fileInput.addEventListener("change", handleFileInputChange);
    fileConfirmButton.addEventListener("click", handleFileConfirm);
    
    // Camera functionality
    captureButton.addEventListener("click", handleCapture);
    
    // Image actions
    document.getElementById("retake-button").addEventListener("click", openCamera);
    document.getElementById("image-confirm-button").addEventListener("click", handleImageConfirm);

    // PDF Download
    document.getElementById("download-pdf").addEventListener("click", generatePDF);

    // Page navigation
    function openPage(page) {
        Object.values(pages).forEach(p => p.classList.add("hidden"));
        page.classList.remove("hidden");
        
        // Add a subtle animation
        page.classList.add("animate-fadeIn");
        setTimeout(() => {
            page.classList.remove("animate-fadeIn");
        }, 500);
    }

    // Camera handling
    function openCamera() {
        openPage(pages.page3);
        if (cameraLoading) cameraLoading.classList.remove("hidden");
        
        const constraints = {
            video: { 
                facingMode: "environment",
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };
        
        navigator.mediaDevices.getUserMedia(constraints)
            .then(stream => {
                currentStream = stream;
                cameraStream.srcObject = stream;
                if (cameraLoading) cameraLoading.classList.add("hidden");
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
                showNotification("دسترسی به دوربین مقدور نیست.", "danger");
                openPage(pages.page1);
            });
    }

    function handleCapture() {
        const canvas = document.createElement("canvas");
        canvas.width = cameraStream.videoWidth;
        canvas.height = cameraStream.videoHeight;
        canvas.getContext("2d").drawImage(cameraStream, 0, 0);
        outputImage.src = canvas.toDataURL("image/png");
        stopCamera();
        openPage(pages.page4);
    }

    function stopCamera() {
        if (currentStream) {
            const tracks = currentStream.getTracks();
            tracks.forEach(track => track.stop());
        }
    }

    // File handling
    function handleFileInputChange() {
        const file = fileInput.files[0];
        if (file) {
            // Enable the confirm button
            fileConfirmButton.disabled = false;
            
            // Show file name
            fileName.textContent = file.name;
            fileName.classList.add("text-secondary");
            
            // Preview the image
            const reader = new FileReader();
            reader.onload = e => {
                outputImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            fileConfirmButton.disabled = true;
            fileName.textContent = "هیچ فایلی انتخاب نشده است";
            fileName.classList.remove("text-secondary");
        }
    }

    function handleFileConfirm() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                outputImage.src = e.target.result;
                openPage(pages.page4);
            };
            reader.readAsDataURL(file);
        } else {
            showNotification("لطفا یک فایل انتخاب کنید!", "danger");
        }
    }

    // Utility functions
    function handleBackToMainFromCamera() {
        stopCamera();
        openPage(pages.page1);
    }

    function handleBackToMainFromPreview() {
        openPage(pages.page1);
    }

    function handleImageConfirm() {
        const dataUrl = outputImage.src;
        
        // Show loading overlay before processing starts
        loadingOverlay.classList.remove("hidden");
        
        setTimeout(() => {
            try {
                if (!dataUrl || dataUrl === '') {
                    throw new Error("تصویری برای تحلیل وجود ندارد");
                }
                
                const blob = dataURLtoBlob(dataUrl);
                
                if (!blob || blob.size === 0) {
                    throw new Error("تصویر انتخاب شده نامعتبر است");
                }
                
                // Check file size - max 5MB
                if (blob.size > 5 * 1024 * 1024) {
                    showNotification("حجم تصویر بسیار زیاد است (حداکثر 5 مگابایت)", "danger");
                    loadingOverlay.classList.add("hidden");
                    return;
                }
                
                // Now send for analysis
                sendImageForAnalysis(blob);
                
            } catch (error) {
                console.error("Error processing image:", error);
                showNotification(error.message || "خطا در پردازش تصویر", "danger");
                loadingOverlay.classList.add("hidden");
            }
        }, 100); // Short delay to allow UI update
    }

    function dataURLtoBlob(dataURL) {
        try {
            // Check if dataURL is valid
            if (!dataURL || typeof dataURL !== 'string' || !dataURL.startsWith('data:')) {
                throw new Error('داده تصویر نامعتبر است');
            }
            
            // Split metadata from base64 data
            const parts = dataURL.split(',');
            if (parts.length !== 2) {
                throw new Error('فرمت داده تصویر نامعتبر است');
            }
            
            const mime = parts[0].match(/:(.*?);/)[1];
            const base64 = parts[1];
            
            // Decode base64
            let byteString;
            try {
                byteString = atob(base64);
            } catch (e) {
                throw new Error('خطا در رمزگشایی تصویر');
            }
            
            // Create array buffer
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            
            // Fill array buffer
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            
            return new Blob([ab], { type: mime });
        } catch (error) {
            console.error('Error in dataURLtoBlob:', error);
            showNotification(error.message || 'خطا در پردازش تصویر', 'danger');
            throw error;
        }
    }

    // API communication
    function sendImageForAnalysis(imageBlob) {
        if (!imageBlob || !(imageBlob instanceof Blob)) {
            showNotification("فایل تصویر نامعتبر است", "danger");
            loadingOverlay.classList.add("hidden");
            return;
        }
        
        console.log(`Sending image for analysis, size: ${(imageBlob.size / 1024).toFixed(2)} KB`);
        
        const formData = new FormData();
        formData.append('file', imageBlob, 'image.jpg'); // Add filename extension

        fetch('{{ route('analyze') }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || `خطای سرور با کد ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // Hide loading overlay
            loadingOverlay.classList.add("hidden");
            
            if (data.error) {
                throw new Error(data.error);
            } else if (!data.result) {
                throw new Error("پاسخی از سرور دریافت نشد");
            } else {
                displayAnalysisResult(data.result);
                openPage(pages.page5);
            }
        })
        .catch(error => {
            // Hide loading overlay
            loadingOverlay.classList.add("hidden");
            console.error("API Error:", error);
            showNotification(error.message || "خطا در ارتباط با سرور", "danger");
        });
    }

    // Results display
    function displayAnalysisResult(result) {
        // خالی کردن جدول قبلی
        const analysisTable = document.getElementById("analysis-table");
        analysisTable.innerHTML = '';
        
        console.log("Raw result:", result); // برای دیباگ
        
        // پارس کردن نتیجه و ایجاد ردیف‌های جدول
        let resultData;
        if (typeof result === 'string') {
            try {
                // تلاش برای پارس کردن به عنوان JSON
                resultData = JSON.parse(result);
            } catch (e) {
                // اگر JSON نبود، از پارسر متن استفاده کنیم
                resultData = parseResultString(result);
            }
        } else {
            // اگر از قبل آبجکت است، از همان استفاده کنیم
            resultData = result;
        }
        
        console.log("Parsed result:", resultData);

    const buyButton = document.getElementById("buy-button");
    if (resultData["کود پیشنهادی"] === "کود۱") {
        buyButton.onclick = () => window.location.href = "https://example.com/buy1";
    } else if (resultData["کود پیشنهادی"] === "کود۲") {
        buyButton.onclick = () => window.location.href = "https://example.com/buy2";
    } else if (resultData["کود پیشنهادی"] === "کود۳") {
        buyButton.onclick = () => window.location.href = "https://example.com/buy3";
    } else {
        buyButton.onclick = () => alert("کود پیشنهادی مشخص نشده");
    }

        
        // تعریف ترتیب مورد نظر برای نمایش فیلدها
        const fieldOrder = [
            "نام فارسی",
            "نام علمی",
            "گروه گیاهی",
            "وضعیت فعلی",
            "شرایط نگهداری",
            "نیاز آبی",
            "نیاز نوری",
            "کود پیشنهادی"
        ];
        
        // فیلدهای موجود در نتیجه
        const availableFields = Object.keys(resultData);
        
        // ابتدا فیلدهای مشخص شده در ترتیب را اضافه کنیم
        let rowIndex = 0;
        fieldOrder.forEach(field => {
            if (resultData[field]) {
                addTableRow(field, resultData[field], rowIndex % 2 === 0, field.includes("کود"));
                rowIndex++;
                
                // حذف فیلد پردازش شده از لیست
                const index = availableFields.indexOf(field);
                if (index > -1) {
                    availableFields.splice(index, 1);
                }
            }
        });
        
        // اضافه کردن هر فیلد باقیمانده که در ترتیب اصلی نبوده
        availableFields.forEach(field => {
            addTableRow(field, resultData[field], rowIndex % 2 === 0, field.includes("کود"));
            rowIndex++;
        });
        
        // اضافه کردن انیمیشن به جدول
        setTimeout(() => {
            document.querySelectorAll('#analysis-table tr').forEach((row, i) => {
                row.style.opacity = '0';
                row.style.animation = `fadeIn 0.3s ease-out ${i * 0.05}s forwards`;
            });
        }, 100);
        
        // تابع کمکی برای اضافه کردن یک ردیف به جدول
        function addTableRow(key, value, isEven, isFertilizer) {
            const row = document.createElement('tr');
            if (isFertilizer) {
                row.className = 'fertilizer-row';
            } else {
                row.className = isEven ? '' : '';
            }
            
            // تبدیل به رشته اگر رشته نباشد
            let strValue = typeof value === 'string' ? value : String(value);
            
            // متن‌های طولانی را با خط شکن نمایش دهیم
            const formattedValue = strValue.replace(/\n/g, '<br>');
            
            if (isFertilizer) {
                // استایل خاص برای ردیف کود
                row.innerHTML = `
                    <td class="whitespace-nowrap font-medium text-gray-900">
                        ${key}
                    </td>
                    <td class="text-gray-700 flex justify-between items-start">
                        <span class="whitespace-pre-wrap break-words">${formattedValue}</span>
                        <button class="copy-btn text-secondary hover:text-secondary/80 p-1 flex-shrink-0 ml-2" 
                                data-content="${strValue.replace(/"/g, '&quot;')}" 
                                title="کپی کردن" 
                                aria-label="کپی کردن متن">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                        </button>
                    </td>
                `;
            } else {
                row.innerHTML = `
                    <td class="whitespace-nowrap font-medium text-gray-900">
                        ${key}
                    </td>
                    <td class="text-gray-700">
                        <span class="whitespace-pre-wrap break-words">${formattedValue}</span>
                    </td>
                `;
            }
            
            analysisTable.appendChild(row);
        }
        
        // اضافه کردن دکمه‌های کپی
        setTimeout(() => {
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const textToCopy = this.getAttribute('data-content');
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showNotification("متن کپی شد", "primary");
                    }).catch(err => {
                        console.error('خطا در کپی کردن متن:', err);
                    });
                });
            });
        }, 500);
    }
    
    function parseResultString(resultString) {
        // تعریف فیلدهای اصلی مورد نیاز در جدول
        const expectedFields = [
            "نام فارسی",
            "نام علمی",
            "گروه گیاهی",
            "شرایط نگهداری",
            "نیاز آبی",
            "نیاز نوری",
            "وضعیت فعلی",
            "کود پیشنهادی"
        ];
        
        // در صورتی که خروجی به صورت JSON باشد
        try {
            const jsonResult = JSON.parse(resultString);
            if (typeof jsonResult === 'object' && jsonResult !== null) {
                return jsonResult;
            }
        } catch (e) {
            // اگر JSON نبود، پردازش متن را ادامه می‌دهیم
        }
        
        // ایجاد فیلدهای پایه برای نتیجه
        const result = {};
        
        // ذخیره محتوای کامل متن برای پردازش یکجا
        let fullText = resultString;
        
        // میرسیم اگر متن با الگوی خاصی جداسازی شده باشد
        // جدا کردن با خط جدید
        const lines = resultString.split('\n').filter(line => line.trim());
        
        // جستجوی الگوهای کلیدی در متن
        for (const field of expectedFields) {
            // بررسی می‌کنیم آیا خط شامل فیلد مورد نظر است یا خیر
            const matchingLine = lines.find(line => 
                line.includes(`${field}:`) || 
                line.includes(`${field}:`) || 
                line.includes(`${field} :`) || 
                line.startsWith(field) ||
                line.includes(` ${field} `)
            );
            
            if (matchingLine) {
                const parts = matchingLine.split(/[:;،]/);
                if (parts.length >= 2) {
                    result[field] = parts.slice(1).join(':').trim();
                    // حذف این خط از متن کامل
                    fullText = fullText.replace(matchingLine, '');
                }
            }
        }
        
        // اگر نام فارسی پیدا نشد، سعی کنیم به روش دیگری پیدا کنیم
        if (!result["نام فارسی"]) {
            const nameLine = lines.find(line => 
                line.includes("گیاه") || 
                line.includes("گل") || 
                line.includes("درخت") ||
                line.includes("درختچه")
            );
            
            if (nameLine) {
                result["نام فارسی"] = nameLine.trim();
                fullText = fullText.replace(nameLine, '');
            }
        }
        
        // بررسی برای "نام علمی" با الگوی حروف لاتین
        if (!result["نام علمی"]) {
            const scientificLine = lines.find(line => 
                /[A-Za-z]/.test(line) && 
                !line.includes("http") && 
                !line.includes("www")
            );
            
            if (scientificLine) {
                result["نام علمی"] = scientificLine.trim();
                fullText = fullText.replace(scientificLine, '');
            }
        }
        
        // بررسی "کود پیشنهادی" - معمولاً در انتهای متن است
        if (!result["کود پیشنهادی"]) {
            const fertilizeLine = lines.find(line => 
                line.includes("کود") || 
                line.includes("تغذیه") || 
                line.includes("NPK") ||
                line.includes("ازت") ||
                line.includes("فسفر") ||
                line.includes("پتاسیم") ||
                line.includes("نیتروژن")
            );
            
            if (fertilizeLine) {
                result["کود پیشنهادی"] = fertilizeLine.trim();
                fullText = fullText.replace(fertilizeLine, '');
            }
        }
        
        // اگر هنوز وضعیت فعلی نداریم، متن باقیمانده را بررسی کنیم
        if (!result["وضعیت فعلی"]) {
            // پیدا کردن خطوطی که به وضعیت فعلی اشاره دارند
            const conditionLines = lines.filter(line => 
                line.includes("وضعیت") || 
                line.includes("حالت") || 
                line.includes("شرایط") ||
                line.includes("سلامت") ||
                line.includes("برگ‌ها") ||
                line.includes("ساقه")
            );
            
            if (conditionLines.length > 0) {
                result["وضعیت فعلی"] = conditionLines.join(" ");
                conditionLines.forEach(line => {
                    fullText = fullText.replace(line, '');
                });
            }
        }
        
        // اگر شرایط نگهداری نداریم
        if (!result["شرایط نگهداری"]) {
            const careLines = lines.filter(line => 
                line.includes("نگهداری") || 
                line.includes("مراقبت") || 
                line.includes("شرایط") ||
                line.includes("رشد")
            );
            
            if (careLines.length > 0) {
                result["شرایط نگهداری"] = careLines.join(" ");
                careLines.forEach(line => {
                    fullText = fullText.replace(line, '');
                });
            }
        }
        
        // اگر هنوز فیلدهای اصلی خالی هستند، باقیمانده متن را در فیلدهای مناسب قرار دهیم
        const remainingText = fullText.trim();
        if (remainingText && !result["وضعیت فعلی"]) {
            result["وضعیت فعلی"] = remainingText;
        }
        
        // اطمینان از وجود حداقل برخی فیلدهای ضروری
        if (Object.keys(result).length === 0) {
            // اگر هیچ فیلدی شناسایی نشد، متن اصلی را برگردانیم
            result["تحلیل"] = resultString;
        }
        
        // مرتب کردن فیلدها در ترتیب مورد نظر
        const sortedResult = {};
        expectedFields.forEach(field => {
            if (result[field]) {
                sortedResult[field] = result[field];
            }
        });
        
        // اضافه کردن هر فیلد دیگری که شناسایی شده اما در لیست اصلی نبوده
        Object.keys(result).forEach(field => {
            if (!expectedFields.includes(field)) {
                sortedResult[field] = result[field];
            }
        });
        
        return sortedResult;
    }
    
    // UI feedback functions
    function showNotification(message, type) {
        const notificationElement = document.getElementById("notification");
        
        // Set notification style based on type
        notificationElement.className = "fixed top-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-xl text-white z-50 transition-all duration-300 shadow-lg";
        
        if (type === "danger") {
            notificationElement.classList.add("bg-danger");
        } else {
            notificationElement.classList.add("bg-primary");
        }
        
        // Set content
        notificationElement.textContent = message;
        
        // Show notification
        notificationElement.classList.remove("hidden");
        notificationElement.classList.remove("opacity-0");
        
        // Hide after 3 seconds
        setTimeout(() => {
            notificationElement.classList.add("opacity-0");
            setTimeout(() => {
                notificationElement.classList.add("hidden");
            }, 300);
        }, 3000);
    }

    // PDF Generation function
    function generatePDF() {
        // Show loading indicator
        showNotification("در حال آماده‌سازی PDF...", "primary");
        
        // Get results data
        const rows = document.querySelectorAll('#analysis-table tr');
        if (rows.length === 0) {
            showNotification("داده‌ای برای دانلود وجود ندارد", "danger");
            return;
        }
        
        // Create a temporary hidden div for PDF content
        const contentDiv = document.createElement('div');
        contentDiv.style.position = 'absolute';
        contentDiv.style.left = '-9999px';
        contentDiv.style.fontFamily = 'Vazir, sans-serif';
        contentDiv.style.direction = 'rtl';
        contentDiv.style.textAlign = 'right';
        contentDiv.innerHTML = `
            <h1 style="color: #F3A6B5; font-size: 24px; margin-bottom: 16px; text-align: center;">نتیجه تحلیل گیاه</h1>
            <p style="font-size: 14px; color: #666; text-align: center; margin-bottom: 24px;">
                تاریخ: ${new Date().toLocaleDateString('fa-IR')}
            </p>
            <div style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #F3A6B5;">
                            <th style="padding: 12px; text-align: right; color: white; font-weight: bold;">ویژگی</th>
                            <th style="padding: 12px; text-align: right; color: white; font-weight: bold;">نتیجه</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${Array.from(rows).map((row, index) => {
                            const cells = row.querySelectorAll('td');
                            const key = cells[0].textContent;
                            let value = cells[1].textContent;
                            
                            // Remove any buttons or extra elements
                            if (cells[1].querySelector('button')) {
                                value = cells[1].querySelector('span').textContent;
                            }
                            
                            return `
                                <tr style="background-color: ${index % 2 === 0 ? 'white' : '#FDFBF6'};">
                                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold;">${key}</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">${value}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.body.appendChild(contentDiv);
        
        // Use html2canvas to render the div
        html2canvas(contentDiv, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            windowWidth: 800,
            windowHeight: 1200
        }).then(canvas => {
            try {
                // Create PDF using jsPDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                
                // Calculate dimensions to fit on A4
                const imgData = canvas.toDataURL('image/png');
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth() - 20; // margins
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                
                // Add image to PDF
                pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth, pdfHeight);
                
                // Save PDF
                pdf.save('تحلیل-گیاه.pdf');
                showNotification("PDF با موفقیت دانلود شد", "primary");
                
                // Clean up
                document.body.removeChild(contentDiv);
            } catch (error) {
                console.error("PDF generation error:", error);
                showNotification("خطا در ایجاد PDF", "danger");
                document.body.removeChild(contentDiv);
            }
        }).catch(error => {
            console.error("Error generating PDF:", error);
            showNotification("خطا در ایجاد PDF", "danger");
            document.body.removeChild(contentDiv);
        });
    }
</script>
</body>
</html>
