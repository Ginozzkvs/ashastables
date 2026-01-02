<!DOCTYPE html>
<html>
<head>
    <title>Staff Activity Panel</title>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <audio id="ok-sound" src="/sounds/success.mp3"></audio>
    <audio id="err-sound" src="/sounds/error.mp3"></audio>
    <style>
        body { font-family: Arial; background: #f4f6f8; padding: 20px }
        .card { background: white; padding: 15px; border-radius: 10px; margin-bottom: 15px }
        .btn { padding: 15px; border-radius: 10px; width: 100%; font-size: 18px }
        .btn-ok { background: #16a34a; color: white }
        .btn-no { background: #dc2626; color: white }
        .disabled { opacity: .5 }
    </style>
    <script>
    let qrScanner;

    const staffActivityComponent = {
        qr: '',
        member: null,
        activities: [],
        error: null,

        startScan() {
            document.getElementById('qr-reader').style.display = 'block';

            qrScanner = new Html5Qrcode("qr-reader");

            qrScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    // Update Alpine.js state
                    this.qr = decodedText;

                    // Stop camera
                    qrScanner.stop();
                    document.getElementById('qr-reader').style.display = 'none';

                    // Auto-load member
                    this.loadMember();
                },
                (error) => {
                    console.error('QR scan error:', error);
                }
            );
        },

        loadMember() {
            if (!this.qr) {
                this.error = 'Please scan a QR code first';
                return;
            }
            
            fetch('/staff/activity/member', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ qr: this.qr })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    this.error = data.error
                    this.member = null
                    this.activities = []
                } else {
                    this.error = null
                    this.member = data.member
                    this.activities = data.activities
                }
            })
            .catch(err => {
                console.error('Error loading member:', err);
                this.error = 'Failed to load member data';
            })
        },

        useActivity(activityId) {
            if (!confirm('Use this activity now?')) return

            fetch('/staff/activity/use', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr: this.qr,
                    activity_id: activityId
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    alert(data.error)
                } else {
                    this.loadMember() // refresh balances
                }
            })
        }
    }

    function showSuccess(message) {
        document.getElementById('ok-sound').play();
        const el = document.getElementById('action-overlay');
        el.className = 'success';
        el.innerHTML = '✔ ' + message;
        el.style.display = 'flex';

        setTimeout(() => el.style.display = 'none', 1500);
    }

    function showError(message) {
        document.getElementById('err-sound').play();
        const el = document.getElementById('action-overlay');
        el.className = 'error';
        el.innerHTML = '✖ ' + message;
        el.style.display = 'flex';

        setTimeout(() => el.style.display = 'none', 1500);
    }
    </script>
</head>

<body x-data="staffActivityComponent">

<h2>🐎 Staff Activity Panel</h2>

<div class="card">
    <input
        id="qr_code"
        x-model="qr"
        @keyup.enter="loadMember"
        placeholder="Scan QR / Enter Code"
        style="width:100%; padding:15px; font-size:18px"
    >
    <button
    @click="startScan()"
    style="padding:15px; width:100%; font-size:18px"
>
    📷 Scan Membership QR
</button>
<div id="qr-reader" style="width:100%; display:none;"></div>
</div>

<template x-if="error">
    <div class="card" style="color:red" x-text="error"></div>
</template>

<template x-if="member">
    <div class="card">
        <h3 x-text="member.name"></h3>
        <p>Valid Until: <span x-text="member.end_date"></span></p>
    </div>
</template>

<template x-for="a in activities" :key="a.id">
    <div class="card">
        <h3 x-text="a.activity.name"></h3>
        <p>Remaining: <strong x-text="a.remaining_count"></strong></p>

        <button
            class="btn btn-ok"
            :class="{ 'disabled': a.remaining_count <= 0 }"
            :disabled="a.remaining_count <= 0"
            @click="useActivity(a.activity_id)"
        >
            USE
        </button>
    </div>
</template>

<script>
function useActivity(activityId) {
    fetch(`/api/use-activity`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            qr_code: document.getElementById('qr_code').value,
            activity_id: activityId
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showSuccess(res.message);
            refreshBalances();
        } else {
            showError(res.message);
        }
    })
    .catch(() => showError('System error'));
}
</script>
<div
    id="action-overlay"
    style="
        position:fixed;
        inset:0;
        display:none;
        align-items:center;
        justify-content:center;
        z-index:9999;
        font-size:32px;
        font-weight:bold;
        color:white;
    "
></div>
<style>
.success {
    background: rgba(0, 160, 90, 0.95);
    animation: pop 0.3s ease;
}

.error {
    background: rgba(200, 40, 40, 0.95);
    animation: shake 0.4s;
}

@keyframes pop {
    from { transform: scale(0.9); opacity:0 }
    to { transform: scale(1); opacity:1 }
}

@keyframes shake {
    0% { transform: translateX(0) }
    25% { transform: translateX(-10px) }
    50% { transform: translateX(10px) }
    75% { transform: translateX(-10px) }
    100% { transform: translateX(0) }
}
</style>

</body>
</html>
