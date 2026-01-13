<!DOCTYPE html>
<html>
<head>
    <title>Staff Activity Panel</title>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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
</head>

<body
x-data="{
    card_uid: '',
    member: null,
    activities: [],
    error: null,

    loadMember() {
        if (!this.card_uid) {
            this.error = 'Please scan RFID card';
            return;
        }

        fetch('/staff/activity/member', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ card_uid: this.card_uid })
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                this.error = data.error;
                this.member = null;
                this.activities = [];
                document.getElementById('err-sound').play();
            } else {
                this.error = null;
                this.member = data.member;
                this.activities = data.activities;
                document.getElementById('ok-sound').play();
            }
        });
    },

    useActivity(activityId) {
        if (!confirm('Use this activity now?')) return;

        fetch('/staff/activity/use', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                card_uid: this.card_uid,
                activity_id: activityId
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                document.getElementById('err-sound').play();
            } else {
                document.getElementById('ok-sound').play();
                this.loadMember(); // refresh balances
            }
        });
    }
}"
>

<h2>🐎 Staff Activity Panel</h2>

<div class="card">
    <input
        x-model="card_uid"
        @keyup.enter="loadMember"
        placeholder="Scan RFID / NFC Card"
        autofocus
        style="width:100%; padding:15px; font-size:20px"
    >
</div>

<template x-if="error">
    <div class="card" style="color:red" x-text="error"></div>
</template>

<template x-if="member">
    <div class="card">
        <h3 x-text="member.name"></h3>
        <p>Valid Until: <strong x-text="member.end_date"></strong></p>
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

</body>
</html>
