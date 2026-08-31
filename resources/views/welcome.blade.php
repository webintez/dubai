@extends('layouts.app')

@section('title', 'Dubai VIP Pass - Premium Access & Registration')

@section('content')
<div class="hero-section">
    <!-- Overlay for content readability -->
    <div class="hero-overlay"></div>
    
    <!-- Hero Content -->
    <div class="hero-container">
        <header class="header">
            <div class="logo">
                <span class="gold-text">DUBAI</span> VIP
            </div>
            <div class="support-badge">
                <a href="tel:{{ $supportPhone }}" class="support-link">
                    <i class="fa-solid fa-headset gold-icon"></i> 
                    <span>Support: <strong class="gold-text">{{ $supportPhone }}</strong></span>
                </a>
            </div>
        </header>

        <div class="hero-body-grid">
            <!-- LEFT COLUMN: Heading & Action Buttons -->
            <div class="hero-left-col">
                <div class="badge-wrapper animate-fade-in">
                    <span class="luxury-badge">OFFICIAL REGISTRATION GATEWAY</span>
                </div>
                <h1 class="hero-title animate-slide-up">
                    Discover the <br>
                    <span class="gold-gradient-text">Ultimate Dubai Experience</span>
                </h1>
                <p class="hero-subtitle animate-slide-up-delay">
                    Secure your exclusive VIP access pass to premium tours, high-end landmarks, and spectacular events. Select your preferred date to register.
                </p>

                <div class="action-buttons-container animate-fade-in-delayed">
                    <!-- Today Button -->
                    @if($todayLink)
                        <a href="{{ $todayLink }}" target="_blank" class="btn btn-outline-gold">
                            <span class="btn-content">
                                <i class="fa-solid fa-calendar-day"></i> TODAY
                            </span>
                        </a>
                    @else
                        <button type="button" class="btn btn-gold btn-glowing open-reg-form" data-day="today" id="btn-select-today">
                            <span class="btn-content">
                                <i class="fa-solid fa-calendar-day"></i> TODAY
                            </span>
                        </button>
                    @endif

                    <!-- Tomorrow Button -->
                    @if($tomorrowLink)
                        <a href="{{ $tomorrowLink }}" target="_blank" class="btn btn-outline-gold">
                            <span class="btn-content">
                                <i class="fa-solid fa-calendar-plus"></i> TOMORROW
                            </span>
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-gold open-reg-form" data-day="tomorrow" id="btn-select-tomorrow">
                            <span class="btn-content">
                                <i class="fa-solid fa-calendar-plus"></i> TOMORROW
                            </span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- RIGHT COLUMN: Registration Form Inline Card -->
            <div class="hero-right-col animate-fade-in-delayed">
                <div class="glass-card" id="registrationCard">
                    
                    <!-- ACTIVE STATE: Form steps (Displayed directly) -->
                    <div class="form-steps-container" id="steps-container">
                        
                        <!-- Step Indicator -->
                        <div class="step-indicator-bar">
                            <div class="step-dot active" id="dot1"><span>1</span></div>
                            <div class="step-line" id="line1"></div>
                            <div class="step-dot" id="dot2"><span>2</span></div>
                            <div class="step-line" id="line2"></div>
                            <div class="step-dot" id="dot3"><span>3</span></div>
                        </div>

                        <!-- STEP 1: Registration Form -->
                        <div class="modal-step active" id="step1">
                            <h2 class="modal-title">VIP Registration</h2>
                            <p class="modal-desc">Please complete the form below to secure your VIP ticket for <strong class="gold-text uppercase" id="selected-day-label">today</strong>.</p>
                            
                            <form id="reg-form">
                                @csrf
                                <input type="hidden" name="day" id="form-day" value="today">
                                
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-user input-icon"></i>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required autocomplete="name">
                                    </div>
                                    <span class="invalid-feedback" id="err-name"></span>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-envelope input-icon"></i>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="johndoe@example.com" required autocomplete="email">
                                    </div>
                                    <span class="invalid-feedback" id="err-email"></span>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone / WhatsApp Number</label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-phone input-icon"></i>
                                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="+971 50 123 4567" required autocomplete="tel">
                                    </div>
                                    <span class="invalid-feedback" id="err-phone"></span>
                                </div>

                                <button type="submit" class="btn btn-gold btn-block" id="btn-submit-step1">
                                    <span>Proceed to Payment <i class="fa-solid fa-arrow-right"></i></span>
                                </button>
                            </form>
                        </div>

                        <!-- STEP 2: QR Code & Payment Upload -->
                        <div class="modal-step" id="step2">
                            <h2 class="modal-title">Complete Payment</h2>
                            <p class="modal-desc text-center">Scan the QR code below using your banking app to transfer the payment.</p>
                            
                            <div class="qr-container">
                                @if($qrUrl)
                                    <img src="{{ $qrUrl }}" alt="Payment QR Code" class="payment-qr-img">
                                @else
                                    <!-- Fallback elegant golden QR template using a styled placeholder if not configured -->
                                    <div class="qr-placeholder">
                                        <i class="fa-solid fa-qrcode qr-icon"></i>
                                        <span class="qr-text">QR Code Awaiting Setup by Admin</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="payment-instructions">
                                <p class="text-sm"><i class="fa-solid fa-circle-info gold-text"></i> Ensure payment includes your registration email as reference. Once transferred, upload a screenshot below to verify.</p>
                            </div>

                            <form id="screenshot-form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="submission_id" id="submission-id" value="">
                                
                                <div class="form-group">
                                    <label class="form-label">Upload Proof of Payment</label>
                                    <div class="file-upload-zone" id="upload-zone">
                                        <input type="file" id="screenshot" name="screenshot" accept="image/*" class="file-input" required>
                                        <div class="upload-content">
                                            <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                                            <span class="upload-text">Click to choose image or drag & drop</span>
                                            <span class="upload-hint">Supported formats: JPG, PNG, WEBP (Max 5MB)</span>
                                        </div>
                                        <div class="preview-container hidden" id="preview-box">
                                            <img src="" id="file-preview" class="file-preview-img">
                                            <button type="button" class="remove-preview-btn" id="remove-file-btn"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                                    </div>
                                    <span class="invalid-feedback text-center" id="err-screenshot"></span>
                                </div>

                                <div class="form-buttons-row">
                                    <button type="submit" class="btn btn-gold btn-block" id="btn-submit-step2">
                                        <span>Confirm Transfer & Complete <i class="fa-solid fa-circle-check"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- STEP 3: Success Confirmation -->
                        <div class="modal-step" id="step3">
                            <div class="success-animation">
                                <div class="success-ring">
                                    <i class="fa-solid fa-check checkmark"></i>
                                </div>
                            </div>
                            <h2 class="modal-title">Verification Pending</h2>
                            <p class="modal-desc text-center">
                                Thank you! Your registration has been submitted successfully.
                            </p>
                            <div class="status-summary-box">
                                <p><strong>Name:</strong> <span id="summary-name">John Doe</span></p>
                                <p><strong>Day:</strong> <span id="summary-day" class="uppercase gold-text">Today</span></p>
                                <p><strong>Status:</strong> <span class="status-badge-pending"><i class="fa-solid fa-hourglass-half"></i> Pending Review</span></p>
                            </div>
                            <p class="text-sm text-center text-muted" style="margin-bottom: 1.5rem;">
                                Our support team is verifying your payment screenshot. You will receive a confirmation message shortly.
                            </p>
                            <button type="button" class="btn btn-outline-gold btn-block" id="reset-card-btn">
                                <span>Register Another Ticket</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Dubai Tourism VIP Gateway. All Rights Reserved. Fully Compatible with Hostinger Shared Hosting.</p>
        </footer>
    </div>
</div>

<!-- Floating Customer Support Widget -->
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supportPhone) }}" target="_blank" class="floating-support" title="WhatsApp Customer Support">
    <div class="support-pulse"></div>
    <i class="fa-brands fa-whatsapp"></i>
</a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openBtns = document.querySelectorAll('.open-reg-form');
        const selectedDayLabel = document.getElementById('selected-day-label');
        const formDay = document.getElementById('form-day');
        
        // Forms
        const regForm = document.getElementById('reg-form');
        const screenshotForm = document.getElementById('screenshot-form');
        
        // File Upload
        const fileInput = document.getElementById('screenshot');
        const uploadZone = document.getElementById('upload-zone');
        const previewBox = document.getElementById('preview-box');
        const filePreview = document.getElementById('file-preview');
        const removeFileBtn = document.getElementById('remove-file-btn');
        const uploadContent = uploadZone.querySelector('.upload-content');

        // Steps & Dots
        const steps = ['step1', 'step2', 'step3'];
        const dots = ['dot1', 'dot2', 'dot3'];
        const lines = ['line1', 'line2'];

        // Determine default day based on available buttons and redirects
        let defaultDay = 'today';
        const todayBtn = document.getElementById('btn-select-today');
        const tomorrowBtn = document.getElementById('btn-select-tomorrow');
        
        if (!todayBtn && tomorrowBtn) {
            defaultDay = 'tomorrow';
            tomorrowBtn.classList.remove('btn-outline-gold');
            tomorrowBtn.classList.add('btn-gold', 'btn-glowing');
        } else if (todayBtn) {
            todayBtn.classList.remove('btn-outline-gold');
            todayBtn.classList.add('btn-gold', 'btn-glowing');
        }
        
        // Initialize form with default day
        selectedDayLabel.textContent = defaultDay;
        formDay.value = defaultDay;

        // Toggle Form Day
        openBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const day = this.getAttribute('data-day');
                
                // Remove active classes from all day buttons
                openBtns.forEach(b => {
                    b.classList.remove('btn-gold', 'btn-glowing');
                    b.classList.add('btn-outline-gold');
                });
                
                // Add active style to selected button
                this.classList.remove('btn-outline-gold');
                this.classList.add('btn-gold', 'btn-glowing');

                selectedDayLabel.textContent = day;
                formDay.value = day;
                
                // Reset form values & errors
                regForm.reset();
                screenshotForm.reset();
                resetValidationErrors();
                resetFileUpload();
                
                // Show Step 1
                goToStep(1);
            });
        });

        // Reset Card / Register another
        document.getElementById('reset-card-btn').addEventListener('click', function() {
            // Reset to default step 1
            regForm.reset();
            screenshotForm.reset();
            resetValidationErrors();
            resetFileUpload();
            
            // Set buttons back to default day selection
            openBtns.forEach(b => {
                b.classList.remove('btn-gold', 'btn-glowing');
                b.classList.add('btn-outline-gold');
            });
            
            if (defaultDay === 'today' && todayBtn) {
                todayBtn.classList.remove('btn-outline-gold');
                todayBtn.classList.add('btn-gold', 'btn-glowing');
            } else if (defaultDay === 'tomorrow' && tomorrowBtn) {
                tomorrowBtn.classList.remove('btn-outline-gold');
                tomorrowBtn.classList.add('btn-gold', 'btn-glowing');
            }
            
            selectedDayLabel.textContent = defaultDay;
            formDay.value = defaultDay;
            
            goToStep(1);
        });

        // Step Navigation Logic
        function goToStep(stepNum) {
            steps.forEach((stepId, idx) => {
                const stepEl = document.getElementById(stepId);
                if (idx + 1 === stepNum) {
                    stepEl.classList.add('active');
                } else {
                    stepEl.classList.remove('active');
                }
            });

            dots.forEach((dotId, idx) => {
                const dotEl = document.getElementById(dotId);
                if (idx + 1 <= stepNum) {
                    dotEl.classList.add('active');
                } else {
                    dotEl.classList.remove('active');
                }
            });

            lines.forEach((lineId, idx) => {
                const lineEl = document.getElementById(lineId);
                if (idx + 1 < stepNum) {
                    lineEl.classList.add('active');
                } else {
                    lineEl.classList.remove('active');
                }
            });
        }

        // Reset Validation Errors
        function resetValidationErrors() {
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid');
            });
            uploadZone.classList.remove('is-invalid');
        }

        // Show Errors
        function showErrors(errors, formPrefix = '') {
            resetValidationErrors();
            for (const [key, messages] of Object.entries(errors)) {
                const errEl = document.getElementById(`err-${key}`);
                const inputEl = document.getElementById(key);
                
                if (errEl) {
                    errEl.textContent = messages[0];
                    errEl.style.display = 'block';
                }
                
                if (inputEl) {
                    inputEl.classList.add('is-invalid');
                }

                if (key === 'screenshot') {
                    uploadZone.classList.add('is-invalid');
                }
            }
        }

        // STEP 1 Form Submission
        regForm.addEventListener('submit', function(e) {
            e.preventDefault();
            resetValidationErrors();
            
            const submitBtn = document.getElementById('btn-submit-step1');
            const originalHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Processing... <i class="fa-solid fa-spinner fa-spin"></i></span>';

            const formData = new FormData(regForm);

            fetch("{{ route('register.submit') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;

                if (res.status === 200 && res.body.success) {
                    // Set Submission ID for Step 2
                    document.getElementById('submission-id').value = res.body.submission_id;
                    
                    // Set details for confirmation screen
                    document.getElementById('summary-name').textContent = document.getElementById('name').value;
                    document.getElementById('summary-day').textContent = formDay.value;

                    // Transition to step 2
                    goToStep(2);
                } else if (res.status === 422) {
                    showErrors(res.body.errors);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                console.error('Error:', error);
                alert('Connection error. Please check your internet connection.');
            });
        });

        // File Input Interactions
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    filePreview.src = e.target.result;
                    uploadContent.classList.add('hidden');
                    previewBox.classList.remove('hidden');
                    uploadZone.classList.add('has-preview');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Drag & Drop
        uploadZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                fileInput.files = e.dataTransfer.files;
                
                // Fire change event
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        // Remove Preview Action
        removeFileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetFileUpload();
        });

        function resetFileUpload() {
            fileInput.value = '';
            filePreview.src = '';
            uploadContent.classList.remove('hidden');
            previewBox.classList.add('hidden');
            uploadZone.classList.remove('has-preview');
        }

        // STEP 2 Form Submission
        screenshotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            resetValidationErrors();

            const submitBtn = document.getElementById('btn-submit-step2');
            const originalHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Uploading... <i class="fa-solid fa-spinner fa-spin"></i></span>';

            const formData = new FormData(screenshotForm);

            fetch("{{ route('register.screenshot') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;

                if (res.status === 200 && res.body.success) {
                    // Transition to step 3 (Success)
                    goToStep(3);
                } else if (res.status === 422) {
                    showErrors(res.body.errors);
                } else {
                    alert('Submission failed. Please check file properties.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                console.error('Error:', error);
                alert('Connection error. Please try again.');
            });
        });
    });
</script>
@endpush
