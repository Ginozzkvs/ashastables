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

<body x-data="scanComponent()" x-init="init(); $nextTick(() => $refs.nfc.focus())">

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
        <p>{{ __('messages.equestrian_resort') }}</p>
        <p>{{ __('messages.staff_activity_management') }}</p>
    </div>

    <!-- NFC SCAN PANEL -->
    <div class="nfc-panel" @click="$refs.nfc.focus()">
        <svg class="nfc-icon" viewBox="0 0 100 100" fill="none">
            <path d="M50 20 L20 80 L80 80 Z" stroke="#d4af37" stroke-width="2.5" fill="none" stroke-linejoin="round"/>
            <line x1="50" y1="20" x2="50" y2="80" stroke="#d4af37" stroke-width="2"/>
            <path d="M32 65 Q28 70 32 75" stroke="#d4af37" stroke-width="1.5" fill="none" stroke-linecap="round"/>
            <path d="M68 65 Q72 70 68 75" stroke="#d4af37" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        </svg>
        
        <h3>{{ __('messages.scan_member_card') }}</h3>
        
        <div x-show="!card_uid">
            <p class="authenticating">{{ __('messages.ready_to_authenticate') }}</p>
        </div>
        
        <div x-show="card_uid" class="uid">
            <p style="margin: 0 0 0.5rem; color: #9ca3af; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em;">{{ __('messages.card_uid') }}</p>
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
                <p class="card-label">{{ __('messages.member') }}</p>
                <h3 x-text="member.name" style="margin: 0;"></h3>
                <p style="color: #6b7280; font-size: 0.75rem; margin-top: 0.5rem; font-family: 'Courier New', monospace;" x-text="member.id"></p>
            </div>
            
            <div style="border-top: 1px solid #d4af37; padding-top: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <p class="card-label">{{ __('messages.valid_until') }}</p>
                    <p style="margin: 0; color: #d1d5db;" x-text="member.expiry_date"></p>
                </div>
                <div>
                    <p class="card-label">{{ __('messages.status') }}</p>
                    <p style="margin: 0; color: #4ade80; font-weight: 700;">{{ __('messages.active') }}</p>
                </div>
            </div>
        </div>
    </template>

    <!-- AUTHENTICATING STATE -->
    <template x-if="!member && !error && card_uid">
        <div class="card-base" style="text-align: center;">
            <p class="authenticating">{{ __('messages.authenticating_member') }}</p>
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
                        <p class="card-label">{{ __('messages.used') }}</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #d1d5db;" x-text="a.used_count || 0"></p>
                    </div>
                    <div>
                        <p class="card-label">{{ __('messages.remaining') }}</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700;" :class="Number(a.remaining_count) > 0 ? 'text-green-400' : 'text-red-400'" 
                           :style="Number(a.remaining_count) > 0 ? 'color: #4ade80;' : 'color: #f87171;'"
                           x-text="a.remaining_count"></p>
                    </div>
                    <div>
                        <p class="card-label">{{ __('messages.total') }}</p>
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
                    <span x-show="Number(a.remaining_count) > 0">{{ __('messages.reserve_session') }}</span>
                    <span x-show="Number(a.remaining_count) <= 0">{{ __('messages.fully_booked') }}</span>
                </button>
            </div>
        </template>
    </template>

    <!-- NO ACTIVITIES MESSAGE -->
    <template x-if="member && activities.length === 0 && !loading">
        <div class="card-base" style="text-align: center; padding: 2rem;">
            <svg class="w-12 h-12 mx-auto mb-4" style="color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 style="color: #d4af37; margin: 0 0 0.5rem;">{{ __('messages.no_activities_configured') }}</h3>
            <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">{{ __('messages.no_activities_configured_desc') }}</p>
        </div>
    </template>

</div>

<!-- MODAL BACKDROP -->
<div x-show="modal.show" x-cloak class="modal-backdrop">
    <div class="modal-box">

        <!-- CONFIRM -->
        <template x-if="modal.type === 'confirm'">
            <div>
                <h3>{{ __('messages.confirm_session') }}</h3>
                <p x-text="modal.message"></p>

                <!-- PRINTER SETTINGS IN MODAL -->
                <div style="background: #0f1419; border: 1px solid #d4af37; padding: 1rem; margin: 1.5rem 0; border-radius: 0;">
                    <p style="color: #d4af37; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; margin: 0 0 0.75rem;">{{ __('messages.print_settings') }}</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0; cursor: pointer; font-size: 0.875rem;">
                            <input type="radio" name="printerType" value="usb" x-model="printerType" style="cursor: pointer;">
                            USB
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0; cursor: pointer; font-size: 0.875rem;">
                            <input type="radio" name="printerType" value="ethernet" x-model="printerType" style="cursor: pointer;">
                            Ethernet
                        </label>
                    </div>
                    
                    <template x-if="printerType === 'usb'">
                        <select x-model="usbPrinterName" style="width: 100%; padding: 0.5rem; background: #1a1f2e; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem; font-size: 0.875rem;">
                            <option value="">{{ __('messages.select_printer') }}</option>
                        </select>
                    </template>
                    
                    <template x-if="printerType === 'ethernet'">
                        <template x-if="ethernetPrinters.length > 0 && defaultEthernetPrinter">
                            <div style="width: 100%; padding: 0.5rem; background: #1a1f2e; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem; font-size: 0.875rem; display: flex; align-items: center; justify-content: space-between;">
                                <span x-text="ethernetPrinters.find(p => p.id.toString() === defaultEthernetPrinter)?.name || '{{ __('messages.not_set') }}'"></span>
                                <span style="font-size: 0.75rem; color: #9ca3af;">({{ __('messages.default') }})</span>
                            </div>
                        </template>
                        <template x-if="!defaultEthernetPrinter || ethernetPrinters.length === 0">
                            <div style="width: 100%; padding: 0.5rem; background: #1a1f2e; border: 1px solid #ef4444; color: #ef4444; border-radius: 0.375rem; font-size: 0.875rem;">
                                {{ __('messages.no_default_printer') }}
                            </div>
                        </template>
                    </template>
                </div>

                <div class="modal-actions">
                    <button class="btn btn-outline" @click="closeModal()">{{ __('messages.cancel') }}</button>
                    <button class="btn btn-gold" @click="confirmUse()">{{ __('messages.confirm_print') }}</button>
                </div>
            </div>
        </template>

        <!-- SUCCESS -->
        <template x-if="modal.type === 'success'">
            <div style="text-align: center;">
                <div class="success-icon">✓</div>
                <h3 class="success-text" style="text-transform: uppercase;">{{ __('messages.session_reserved') }}</h3>
                <p style="color: #d1d5db; font-size: 0.875rem;" x-text="modal.message"></p>
            </div>
        </template>

        <!-- ERROR -->
        <template x-if="modal.type === 'error'">
            <div style="text-align: center;">
                <div class="error-icon">✕</div>
                <h3 class="error-text" style="text-transform: uppercase;">{{ __('messages.error') }}</h3>
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
        loading: false,

        // Ethernet printers list and default
        ethernetPrinters: JSON.parse(localStorage.getItem('ethernetPrinters') || '[]'),
        defaultEthernetPrinter: localStorage.getItem('defaultEthernetPrinter') || '',
        
        // Printer settings - default to ethernet if default printer is set, otherwise usb
        get defaultPrinterType() {
            return this.defaultEthernetPrinter ? 'ethernet' : 'usb'
        },
        printerType: null,
        usbPrinterName: localStorage.getItem('usbPrinterName') || '',
        ethernetIP: localStorage.getItem('ethernetIP') || '',

        scanTimer: null,
        scanLocked: false,

        modal: {
            show: false,
            type: 'confirm',
            message: '',
            activityId: null,
        },
        
        init() {
            // Initialize printerType based on defaultEthernetPrinter
            this.printerType = this.defaultPrinterType
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
            this.loading = true
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
                this.loading = false
                console.log('Full API response:', data) // Debug full response
                console.log('Activities from API:', data.activities) // Debug activities
                console.log('Activities type:', typeof data.activities) // Debug type
                console.log('Activities is array:', Array.isArray(data.activities)) // Check if array
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
                    // Force convert to array if needed
                    this.activities = Array.isArray(data.activities) ? data.activities : Object.values(data.activities || {})
                    console.log('Activities set:', this.activities.length, this.activities) // Debug
                    this.scanLocked = false
                }
            })
            .catch(err => {
                this.loading = false
                this.error = '{{ __('messages.network_error') }}'
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
                message: '{{ __('messages.reserve_this_session') }}',
                activityId
            }
        },

        closeModal() {
            this.modal.show = false
        },

        async confirmUse() {
            // Save printer settings
            localStorage.setItem('printerType', this.printerType)
            if (this.printerType === 'usb') {
                localStorage.setItem('usbPrinterName', this.usbPrinterName)
            } else {
                localStorage.setItem('ethernetIP', this.ethernetIP)
            }

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
                    
                    // Print receipt if data provided
                    if (data.member && data.activity) {
                        const now = new Date()
                        const timestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString()
                        
                        this.printBill({
                            member_name: data.member.name,
                            member_id: data.member.id,
                            activity_name: data.activity.name,
                            remaining_sessions: data.remaining_sessions,
                            used_sessions: data.used_sessions,
                            timestamp: timestamp
                        })
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
                this.modal.message = '{{ __('messages.system_error') }}'
                setTimeout(() => {
                    this.reset()
                    location.reload()
                }, 1500)
            }
        },

        printBill(receiptData) {
            // Send print request with actual receipt data
            const printData = {
                type: this.printerType,
                receipt: receiptData
            }
            
            if (this.printerType === 'usb') {
                printData.printer_name = this.usbPrinterName
            } else {
                // Use default Ethernet printer
                if (this.defaultEthernetPrinter) {
                    const defaultPrinter = this.ethernetPrinters.find(p => p.id.toString() === this.defaultEthernetPrinter)
                    if (defaultPrinter) {
                        printData.ip_address = defaultPrinter.ip
                    }
                }
            }

            console.log('Printing with data:', printData)
            
            fetch('{{ route("staff.printer.print-receipt") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(printData)
            })
            .then(r => r.json())
            .then(result => {
                console.log('Print response:', result);
                if (!result.success) {
                    alert('Print failed: ' + (result.message || 'unknown error'));
                }
            })
            .catch(err => {
                console.error('Print error:', err);
                alert('Print failed: network or server error');
            })
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
