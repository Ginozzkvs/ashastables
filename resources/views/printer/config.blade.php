@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 3rem auto; padding: 2rem;">
    <h1 style="color: #d4af37; font-size: 2.5rem; margin-bottom: 2rem;">Printer Configuration</h1>

    <div style="background: #1a1f2e; border: 1px solid #d4af37; padding: 2rem; border-radius: 0.5rem;">

        <!-- Connection Type Selection -->
        <div style="margin-bottom: 2rem;">
            <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 1rem;">Connection Type:</label>
            <div style="display: flex; gap: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0; cursor: pointer;">
                    <input type="radio" name="connectionType" value="usb" checked onchange="toggleConnectionType('usb')">
                    USB Printer
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0; cursor: pointer;">
                    <input type="radio" name="connectionType" value="ethernet" onchange="toggleConnectionType('ethernet')">
                    Ethernet Printer
                </label>
            </div>
        </div>

        <!-- USB Configuration -->
        <div id="usbSection" style="margin-bottom: 2rem;">
            <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 1rem;">Select Printer:</label>
            <select id="printerSelect" style="width: 100%; padding: 0.75rem; background: #0f1419; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem;">
                <option value="">Loading printers...</option>
            </select>
            <button onclick="refreshPrinters()" style="margin-top: 1rem; padding: 0.75rem 1.5rem; background: #d4af37; color: #0f1419; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                Refresh List
            </button>
        </div>

        <!-- Ethernet Configuration -->
        <div id="ethernetSection" style="display: none; margin-bottom: 2rem;">
            <!-- Select Default Printer -->
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 0.5rem;">Default Printer:</label>
                <select id="defaultPrinter" style="width: 100%; padding: 0.75rem; background: #0f1419; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem;">
                    <option value="">No printers saved</option>
                </select>
            </div>

            <!-- Add New IP -->
            <div style="margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #d4af37;">
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 0.5rem;">Add New Printer:</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="newPrinterIP" placeholder="192.168.1.100" style="flex: 1; padding: 0.75rem; background: #0f1419; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem;">
                    <input type="text" id="newPrinterName" placeholder="Printer Name" style="flex: 1; padding: 0.75rem; background: #0f1419; border: 1px solid #d4af37; color: #e0e0e0; border-radius: 0.375rem;">
                    <button onclick="addPrinterIP()" style="padding: 0.75rem 1.5rem; background: #10b981; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                        Add
                    </button>
                </div>
            </div>

            <!-- Saved Printers List -->
            <div>
                <label style="display: block; color: #d4af37; font-weight: 600; margin-bottom: 1rem;">Saved Printers:</label>
                <div id="printersList" style="max-height: 300px; overflow-y: auto;">
                    <p style="color: #6b7280; font-size: 0.9rem;">No printers saved</p>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        <div id="statusMessage" style="margin-bottom: 1rem; padding: 1rem; border-radius: 0.375rem; display: none;">
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem;">
            <button onclick="testPrinter()" style="flex: 1; padding: 1rem; background: #d4af37; color: #0f1419; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 1rem;">
                Test Connection
            </button>
            <button onclick="printTestReceipt()" style="flex: 1; padding: 1rem; background: #10b981; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; font-size: 1rem;">
                Print Test Receipt
            </button>
        </div>

        <!-- Information -->
        <div style="margin-top: 2rem; padding: 1rem; background: rgba(212, 175, 55, 0.1); border: 1px solid #d4af37; border-radius: 0.375rem; color: #d1d5db; font-size: 0.9rem;">
            <p><strong>USB Printer:</strong> Works with USB thermal printers connected directly to this computer</p>
            <p style="margin-top: 0.5rem;"><strong>Ethernet Printer:</strong> Works with network thermal printers (POS style)</p>
        </div>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    let printersList = JSON.parse(localStorage.getItem('ethernetPrinters') || '[]');
    let defaultPrinter = localStorage.getItem('defaultEthernetPrinter') || '';

    function toggleConnectionType(type) {
        document.getElementById('usbSection').style.display = type === 'usb' ? 'block' : 'none';
        document.getElementById('ethernetSection').style.display = type === 'ethernet' ? 'block' : 'none';
        clearStatus();
    }

    function refreshPrinters() {
        fetch('{{ route("printer.usb") }}')
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('printerSelect');
                select.innerHTML = '';

                if (data.printers && data.printers.length > 0) {
                    data.printers.forEach(printer => {
                        const option = document.createElement('option');
                        option.value = printer;
                        option.textContent = printer;
                        select.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No printers found';
                    select.appendChild(option);
                }
            })
            .catch(err => showStatus('Failed to load printers', 'error'));
    }

    function addPrinterIP() {
        const ip = document.getElementById('newPrinterIP').value.trim();
        const name = document.getElementById('newPrinterName').value.trim();

        if (!ip) {
            showStatus('Please enter an IP address', 'error');
            return;
        }

        if (!name) {
            showStatus('Please enter a printer name', 'error');
            return;
        }

        // Check if IP already exists
        if (printersList.some(p => p.ip === ip)) {
            showStatus('This IP address is already saved', 'error');
            return;
        }

        printersList.push({ id: Date.now(), ip, name });
        savePrinters();
        
        document.getElementById('newPrinterIP').value = '';
        document.getElementById('newPrinterName').value = '';
        showStatus('Printer added successfully!', 'success');
    }

    function editPrinterIP(id) {
        const printer = printersList.find(p => p.id === id);
        if (!printer) return;

        const newIP = prompt('Edit IP address:', printer.ip);
        if (!newIP) return;

        const newName = prompt('Edit printer name:', printer.name);
        if (!newName) return;

        if (printersList.some(p => p.ip === newIP && p.id !== id)) {
            showStatus('This IP address is already saved', 'error');
            return;
        }

        printer.ip = newIP.trim();
        printer.name = newName.trim();
        savePrinters();
        showStatus('Printer updated!', 'success');
    }

    function deletePrinterIP(id) {
        if (!confirm('Delete this printer?')) return;

        printersList = printersList.filter(p => p.id !== id);
        
        // Reset default if deleted
        if (defaultPrinter === id.toString()) {
            defaultPrinter = '';
            localStorage.removeItem('defaultEthernetPrinter');
        }

        savePrinters();
        showStatus('Printer deleted!', 'success');
    }

    function setDefaultPrinter(id) {
        defaultPrinter = id.toString();
        localStorage.setItem('defaultEthernetPrinter', defaultPrinter);
        renderPrintersList();
        showStatus('Default printer set!', 'success');
    }

    function savePrinters() {
        localStorage.setItem('ethernetPrinters', JSON.stringify(printersList));
        renderPrintersList();
    }

    function renderPrintersList() {
        const container = document.getElementById('printersList');
        const selectDefault = document.getElementById('defaultPrinter');

        selectDefault.innerHTML = '<option value="">None</option>';

        if (printersList.length === 0) {
            container.innerHTML = '<p style="color: #6b7280; font-size: 0.9rem;">No printers saved</p>';
            return;
        }

        container.innerHTML = printersList.map(printer => `
            <div style="background: #0f1419; border: 1px solid #d4af37; padding: 1rem; margin-bottom: 0.5rem; border-radius: 0.375rem;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <p style="margin: 0; color: #d4af37; font-weight: 600;">${printer.name}</p>
                        <p style="margin: 0.25rem 0 0; color: #9ca3af; font-size: 0.85rem; font-family: 'Courier New', monospace;">${printer.ip}</p>
                        ${defaultPrinter === printer.id.toString() ? '<p style="margin: 0.5rem 0 0; color: #10b981; font-size: 0.8rem; font-weight: 600;">DEFAULT</p>' : ''}
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        ${defaultPrinter !== printer.id.toString() ? `<button onclick="setDefaultPrinter(${printer.id})" style="padding: 0.5rem 0.75rem; background: #10b981; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.8rem;">Set Default</button>` : ''}
                        <button onclick="editPrinterIP(${printer.id})" style="padding: 0.5rem 0.75rem; background: #d4af37; color: #0f1419; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.8rem;">Edit</button>
                        <button onclick="deletePrinterIP(${printer.id})" style="padding: 0.5rem 0.75rem; background: #ef4444; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-size: 0.8rem;">Delete</button>
                    </div>
                </div>
            </div>
        `).join('');

        // Update default printer dropdown
        printersList.forEach(printer => {
            const option = document.createElement('option');
            option.value = printer.id;
            option.textContent = `${printer.name} (${printer.ip})`;
            option.selected = defaultPrinter === printer.id.toString();
            selectDefault.appendChild(option);
        });

        selectDefault.onchange = () => {
            if (selectDefault.value) {
                setDefaultPrinter(selectDefault.value);
            }
        };
    }

    function testPrinter() {
        const type = document.querySelector('input[name="connectionType"]:checked').value;
        const data = { type };

        if (type === 'usb') {
            data.printer_name = document.getElementById('printerSelect').value;
        } else {
            const selectedId = document.getElementById('defaultPrinter').value;
            if (!selectedId) {
                showStatus('Please select a printer', 'error');
                return;
            }
            const printer = printersList.find(p => p.id.toString() === selectedId);
            data.ip_address = printer.ip;
        }

        fetch('{{ route("printer.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showStatus('Printer connected successfully!', 'success');
                } else {
                    showStatus(data.message, 'error');
                }
            })
            .catch(err => showStatus('Failed to test printer', 'error'));
    }

    function printTestReceipt() {
        const type = document.querySelector('input[name="connectionType"]:checked').value;
        const data = { type };

        if (type === 'usb') {
            data.printer_name = document.getElementById('printerSelect').value;
        } else {
            const selectedId = document.getElementById('defaultPrinter').value;
            if (!selectedId) {
                showStatus('Please select a printer', 'error');
                return;
            }
            const printer = printersList.find(p => p.id.toString() === selectedId);
            data.ip_address = printer.ip;
        }

        fetch('{{ route("printer.print-test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showStatus('Test receipt printed!', 'success');
                } else {
                    showStatus(data.message, 'error');
                }
            })
            .catch(err => showStatus('Failed to print', 'error'));
    }

    function showStatus(message, type) {
        const el = document.getElementById('statusMessage');
        el.textContent = message;
        el.style.display = 'block';
        el.style.background = type === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)';
        el.style.borderLeft = `4px solid ${type === 'success' ? '#10b981' : '#ef4444'}`;
        el.style.color = type === 'success' ? '#10b981' : '#ef4444';
    }

    function clearStatus() {
        document.getElementById('statusMessage').style.display = 'none';
    }

    // Load printers on page load
    window.addEventListener('load', () => {
        refreshPrinters();
        renderPrintersList();
    });
</script>
@endsection
