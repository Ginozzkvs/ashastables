@extends('layouts.app')

@section('content')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Inter', sans-serif; }
    h1, h2, h3 { font-family: 'Cormorant Garamond', serif; letter-spacing: -1px; font-weight: 600; }
    
    [x-cloak] { display: none !important; }
    
    body {
        background: #0f1419;
        color: #fff;
        padding: 0;
        margin: 0;
    }

    .container {
        max-width: 640px;
        margin: 0 auto;
        padding: 1rem 2rem;
    }

    .header {
        text-align: center;
        margin-bottom: 3rem;
        border-bottom: 1px solid #ca8a04;
        padding-bottom: 2rem;
    }

    .logo-svg {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
    }

    .header h1 {
        font-size: 3rem;
        color: #fff;
        margin: 0 0 0.75rem;
    }

    .header p {
            color: #d1d5db;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            margin: 0.25rem 0;
        }

        .nfc-panel {
            border: 1px solid #d4af37;
            transition: all 0.3s ease;
            background: #1a1f2e;
            border-radius: 0;
            padding: 3rem;
            cursor: pointer;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .nfc-panel:hover {
            border-color: #e6c547;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.15);
        }

        .nfc-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 1.5rem;
        }

        .nfc-panel h3 {
            font-size: 1.5rem;
            color: #fff;
            margin: 0 0 0.75rem;
            font-weight: 700;
        }

        .nfc-panel p {
            font-size: 0.875rem;
            color: #9ca3af;
            margin: 0;
        }

        .uid {
            margin-top: 1.5rem;
            font-weight: 700;
            color: #d4af37;
            font-size: 0.875rem;
            letter-spacing: 0.1em;
            font-family: 'Courier New', monospace;
        }

        .card-base {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-radius: 0;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .card-base h3 {
            font-size: 1.25rem;
            color: #fff;
            margin: 0 0 0.5rem;
        }

        .card-base p {
            color: #d1d5db;
            font-size: 0.875rem;
            margin: 0.5rem 0;
        }

        .card-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #d4af37;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .error-message {
            border: 1px solid #ef4444;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 0;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #fca5a5;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            width: 100%;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s;
        }

        .btn-gold {
            background: #d4af37;
            color: #0f1419;
        }
        
        .btn-gold:hover {
            background: #e6c547;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.2);
        }

        .btn-outline {
            border: 1px solid #d4af37;
            color: #d4af37;
            background: transparent;
        }
        
        .btn-outline:hover {
            background: rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }

        .btn-disabled {
            background: rgba(212, 175, 55, 0.3);
            color: #666;
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* MODAL */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 1rem;
        }

        .modal-box {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            padding: 2rem;
            border-radius: 0;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.5);
        }

        .modal-box h3 {
            font-size: 1.25rem;
            color: #fff;
            margin: 0 0 1rem;
        }

        .modal-box p {
            color: #d1d5db;
            font-size: 0.875rem;
            margin: 0 0 2rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .modal-actions button {
            flex: 1;
        }

        .success-icon,
        .error-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            border: 1px solid;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .success-icon {
            border-color: #10b981;
        }

        .error-icon {
            border-color: #ef4444;
        }

        .success-text {
            color: #10b981;
            font-size: 1.125rem;
            font-weight: 700;
            margin: 0;
        }

        .error-text {
            color: #ef4444;
            font-size: 1.125rem;
            font-weight: 700;
            margin: 0;
        }

        .authenticating {
            color: #9ca3af;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
    </style>
</head>

<body x-data="scanComponent()" x-init="$nextTick(() => $refs.nfc.focus())">

<div class="container">
    <!-- HEADER WITH LOGO -->
    <div class="header">
        <!-- GEOMETRIC TENT LOGO -->
        <svg class="logo-svg" viewBox="0 0 100 100" fill="none">
            <path d="M50 10 L10 90 L90 90 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
            <path d="M50 30 L25 80 L75 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none"/>
            <line x1="50" y1="10" x2="50" y2="90" stroke="#d4af37" stroke-width="2"/>
            <path d="M20 70 Q15 75 20 80" stroke="#d4af37" stroke-width="2" fill="none" stroke-linecap="round"/>
            <path d="M80 70 Q85 75 80 80" stroke="#d4af37" stroke-width="2" fill="none" stroke-linecap="round"/>
        </svg>
        
        <h1>ASHA</h1>
        <p>EQUESTRIAN RESORT</p>
        <p>STAFF ACTIVITY MANAGEMENT</p>
    </div>

    <!-- NFC SCAN PANEL -->
    <div class="nfc-panel" @click="$refs.nfc.focus()">
        <svg class="nfc-icon" viewBox="0 0 100 100" fill="none">
            <path d="M50 20 L20 80 L80 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none" stroke-linejoin="round"/>
            <line x1="50" y1="20" x2="50" y2="80" stroke="#d4af37" stroke-width="2"/>
            <path d="M32 65 Q28 70 32 75" stroke="#d4af37" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            <path d="M68 65 Q72 70 68 75" stroke="#d4af37" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        </svg>
        
        <h3>SCAN MEMBER CARD</h3>
        
        <div x-show="!card_uid">
            <p class="authenticating">READY TO AUTHENTICATE</p>
        </div>
        
        <div x-show="card_uid" class="uid">
            <p style="margin: 0 0 0.5rem; color: #9ca3af; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em;">CARD UID</p>
            <p style="margin: 0; color: #d4af37; letter-spacing: 0.1em;" x-text="card_uid"></p>
        </div>

        <input
            x-ref="nfc"
            x-model="card_uid"
            @input="onScanInput"
            type="text"
            autocomplete="off"
            style="position:absolute; opacity:0; width:1px; height:1px;"
        >
    </div>

    <!-- ERROR MESSAGE -->
    <template x-if="error">
        <div class="error-message" x-text="error"></div>
    </template>

    <!-- MEMBER INFO -->
    <template x-if="member">
        <div class="card-base">
            <div style="margin-bottom: 1.5rem;">
                <p class="card-label">Member</p>
                <h3 x-text="member.name" style="margin: 0;"></h3>
                <p style="color: #6b7280; font-size: 0.75rem; margin-top: 0.5rem; font-family: 'Courier New', monospace;" x-text="member.id"></p>
            </div>
            
            <div style="border-top: 1px solid #d4af37; padding-top: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <p class="card-label">Valid Until</p>
                    <p style="margin: 0; color: #d1d5db;" x-text="member.expiry_date"></p>
                </div>
                <div>
                    <p class="card-label">Status</p>
                    <p style="margin: 0; color: #4ade80; font-weight: 700;">ACTIVE</p>
                </div>
            </div>
        </div>
    </template>

    <!-- AUTHENTICATING STATE -->
    <template x-if="!member && !error && card_uid">
        <div class="card-base" style="text-align: center;">
            <p class="authenticating">AUTHENTICATING MEMBER...</p>
        </div>
    </template>

    <!-- ACTIVITIES LIST -->
    <template x-if="activities.length > 0">
        <template x-for="a in activities" :key="a.id">
            <div class="card-base">
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="margin: 0 0 0.5rem;" x-text="a.activity.name"></h3>
                    <p style="color: #6b7280; font-size: 0.75rem; margin: 0; font-family: 'Courier New', monospace;" x-text="'ID: ' + a.activity.id"></p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #d4af37;">
                    <div>
                        <p class="card-label">Used</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #d1d5db;" x-text="a.used_count || 0"></p>
                    </div>
                    <div>
                        <p class="card-label">Remaining</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700;" :class="Number(a.remaining_count) > 0 ? 'text-green-400' : 'text-red-400'" 
                           :style="Number(a.remaining_count) > 0 ? 'color: #4ade80;' : 'color: #f87171;'"
                           x-text="a.remaining_count"></p>
                    </div>
                    <div>
                        <p class="card-label">Total</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #d1d5db;" x-text="Number(a.remaining_count) + (a.used_count || 0)"></p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="width: 100%; height: 4px; background: rgba(212, 175, 55, 0.2);">
                        <div style="height: 4px; background: #d4af37; transition: width 0.3s;"
                             :style="'width: ' + ((a.used_count || 0) / ((a.used_count || 0) + Number(a.remaining_count)) * 100) + '%'">
                        </div>
                    </div>
                </div>

                <!-- RESERVE BUTTON -->
                <button
                    :disabled="Number(a.remaining_count) <= 0"
                    @click="openConfirm(a.activity.id)"
                    :class="Number(a.remaining_count) > 0 ? 'btn btn-gold' : 'btn btn-disabled'"
                >
                    <span x-show="Number(a.remaining_count) > 0">Reserve Session</span>
                    <span x-show="Number(a.remaining_count) <= 0">Fully Booked</span>
                </button>
            </div>
        </template>
    </template>

</div>

<!-- MODAL BACKDROP -->
<div x-show="modal.show" x-cloak class="modal-backdrop">
    <div class="modal-box">

        <!-- CONFIRM -->
        <template x-if="modal.type === 'confirm'">
            <div>
                <h3>CONFIRM SESSION</h3>
                <p x-text="modal.message"></p>

                <div class="modal-actions">
                    <button class="btn btn-outline" @click="closeModal()">Cancel</button>
                    <button class="btn btn-gold" @click="confirmUse()">Confirm</button>
                </div>
            </div>
        </template>

        <!-- SUCCESS -->
        <template x-if="modal.type === 'success'">
            <div style="text-align: center;">
                <div class="success-icon">✓</div>
                <h3 class="success-text" style="text-transform: uppercase;">SESSION RESERVED</h3>
                <p style="color: #d1d5db; font-size: 0.875rem;" x-text="modal.message"></p>
            </div>
        </template>

        <!-- ERROR -->
        <template x-if="modal.type === 'error'">
            <div style="text-align: center;">
                <div class="error-icon">✕</div>
                <h3 class="error-text" style="text-transform: uppercase;">ERROR</h3>
                <p style="color: #d1d5db; font-size: 0.875rem;" x-text="modal.message"></p>
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
                    // Auto-reset after showing error
                    setTimeout(() => {
                        this.card_uid = ''
                        this.error = null
                        this.scanLocked = false
                        this.$refs.nfc.focus()
                    }, 2000)
                } else {
                    this.error = null
                    this.member = data.member
                    this.activities = data.activities
                    this.scanLocked = false
                }
            })
            .catch(err => {
                this.error = 'Network error'
                this.scanLocked = false
                setTimeout(() => {
                    this.card_uid = ''
                    this.error = null
                    this.$refs.nfc.focus()
                }, 2000)
            })
        },

        openConfirm(activityId) {
            this.modal = {
                show: true,
                type: 'confirm',
                message: 'Reserve this session?',
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
                    
                    // Print bill if receipt URL provided
                    if (data.receipt_url) {
                        this.printBill(data.receipt_url)
                    }
                    
                    // Reset after showing success with enough time for print, then refresh page
                    setTimeout(() => {
                        this.reset()
                        location.reload()
                    }, 2500)
                } else {
                    this.modal.type = 'error'
                    this.modal.message = data.message
                    setTimeout(() => {
                        this.reset()
                        location.reload()
                    }, 1500)
                }

            } catch {
                this.modal.type = 'error'
                this.modal.message = 'System error'
                setTimeout(() => {
                    this.reset()
                    location.reload()
                }, 1500)
            }
        },

        printBill(url) {
            const printWindow = window.open(url, 'print', 'width=400,height=600')
            if (printWindow) {
                printWindow.onload = () => {
                    setTimeout(() => {
                        printWindow.print()
                        setTimeout(() => printWindow.close(), 500)
                    }, 300)
                }
            }
        },

        reset() {
            this.modal.show = false
            this.card_uid = ''
            this.member = null
            this.activities = []
            this.error = null
            this.scanLocked = false
            
            // Clear any pending timers
            clearTimeout(this.scanTimer)

            // Re-focus the NFC input to enable card reading again
            this.$nextTick(() => {
                if (this.$refs.nfc) {
                    this.$refs.nfc.value = ''
                    this.$refs.nfc.focus()
                }
            })
        }
    }
}
</script>

@endsection
