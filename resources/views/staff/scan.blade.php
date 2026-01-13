<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Activity Panel</title>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        .btn {
            padding: 14px;
            border-radius: 10px;
            width: 100%;
            font-size: 18px;
            border: none;
            cursor: pointer;
        }

        .btn-ok { background: #16a34a; color: #fff; }
        .btn-disabled { background: #9ca3af; color: #fff; cursor: not-allowed; }

        /* NFC PANEL */
        .nfc-panel {
            text-align: center;
            padding: 40px 20px;
            border: 2px dashed #2563eb;
            border-radius: 16px;
            cursor: pointer;
        }

        .nfc-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .uid {
            margin-top: 10px;
            font-weight: bold;
            color: #2563eb;
            font-size: 18px;
        }

        /* MODAL */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal-box {
            background: white;
            padding: 20px;
            border-radius: 14px;
            width: 320px;
            text-align: center;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .success-text {
            color: #16a34a;
            font-size: 20px;
            font-weight: bold;
        }

        .error-text {
            color: #dc2626;
            font-size: 18px;
            font-weight: bold;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="scanComponent()" x-init="$nextTick(() => $refs.nfc.focus())">

<h2>🐎 Staff Activity Panel</h2>

<!-- NFC SCAN PANEL -->
<div class="card nfc-panel" @click="$refs.nfc.focus()">
    <div class="nfc-icon">📶</div>
    <h3>Tap NFC Card</h3>
    <p x-show="!card_uid">Ready to scan</p>
    <p x-show="card_uid" class="uid" x-text="card_uid"></p>

    <!-- Hidden input for NFC reader -->
    <input
        x-ref="nfc"
        x-model="card_uid"
        @input="onScanInput"
        type="text"
        autocomplete="off"
        style="position:absolute; opacity:0; width:1px; height:1px;"
    >
</div>

<!-- ERROR -->
<template x-if="error">
    <div class="card" style="color:red" x-text="error"></div>
</template>

<!-- MEMBER INFO -->
<template x-if="member">
    <div class="card">
        <h3 x-text="member.name"></h3>
        <p>Valid Until: <strong x-text="member.end_date"></strong></p>
    </div>
</template>

<!-- ACTIVITIES -->
<template x-for="a in activities" :key="a.id">
    <div class="card">
        <h3 x-text="a.activity.name"></h3>
        <p>Remaining: <strong x-text="a.remaining_count"></strong></p>

        <button
            :class="Number(a.remaining_count) > 0 ? 'btn btn-ok' : 'btn btn-disabled'"
            :disabled="Number(a.remaining_count) <= 0"
            @click="openConfirm(a.activity.id)"
        >
            USE
        </button>
    </div>
</template>

<!-- MODAL -->
<div x-show="modal.show" x-cloak class="modal-backdrop">
    <div class="modal-box">

        <!-- CONFIRM -->
        <template x-if="modal.type === 'confirm'">
            <div>
                <h3>Confirm</h3>
                <p x-text="modal.message"></p>

                <div class="modal-actions">
                    <button class="btn btn-disabled" @click="closeModal()">Cancel</button>
                    <button class="btn btn-ok" @click="confirmUse()">Confirm</button>
                </div>
            </div>
        </template>

        <!-- SUCCESS -->
        <template x-if="modal.type === 'success'">
            <div class="success-text">
                ✔ <span x-text="modal.message"></span>
            </div>
        </template>

        <!-- ERROR -->
        <template x-if="modal.type === 'error'">
            <div class="error-text">
                ✖ <span x-text="modal.message"></span>
            </div>
        </template>

    </div>
</div>

<script>
function scanComponent() {
    return {
        card_uid: '',
        member: null,
        activities: [],
        error: null,

        scanTimer: null,
        scanLocked: false,

        modal: {
            show: false,
            type: 'confirm',
            message: '',
            activityId: null,
        },

        onScanInput() {
            if (this.scanLocked) return

            clearTimeout(this.scanTimer)

            this.scanTimer = setTimeout(() => {
                if (this.card_uid.length >= 4) {
                    this.scanLocked = true
                    this.loadMember()
                }
            }, 250)
        },

        loadMember() {
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
                    this.error = data.error
                    this.member = null
                    this.activities = []
                } else {
                    this.error = null
                    this.member = data.member
                    this.activities = data.activities
                }
            })
        },

        openConfirm(activityId) {
            this.modal = {
                show: true,
                type: 'confirm',
                message: 'Use this activity now?',
                activityId
            }
        },

        closeModal() {
            this.modal.show = false
        },

        async confirmUse() {
            try {
                const res = await fetch('/staff/activity/use', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        card_uid: this.card_uid,
                        activity_id: this.modal.activityId
                    })
                })

                const data = await res.json()

                if (data.success) {
                    this.modal.type = 'success'
                    this.modal.message = data.message
                } else {
                    this.modal.type = 'error'
                    this.modal.message = data.message
                }

                setTimeout(() => this.reset(), 1500)

            } catch {
                this.modal.type = 'error'
                this.modal.message = 'System error'
                setTimeout(() => this.reset(), 1500)
            }
        },

        reset() {
            this.modal.show = false
            this.card_uid = ''
            this.member = null
            this.activities = []
            this.error = null
            this.scanLocked = false

            this.$nextTick(() => this.$refs.nfc.focus())
        }
    }
}
</script>

</body>
</html>
