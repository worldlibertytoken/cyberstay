@once
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5.0.5/dist/tesseract.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ocrContainer = document.getElementById('cnic-ocr-container');
    if (!ocrContainer) return;

    let ocrWorker = null;

    async function getWorker() {
        if (!ocrWorker) {
            ocrWorker = await Tesseract.createWorker('eng');
        }

        return ocrWorker;
    }

    function getSideFromInputName(name) {
        if (name.includes('id_card_front')) return 'front';
        if (name.includes('id_card_back')) return 'back';

        return null;
    }

    // Listen for file uploads on CNIC image inputs
    document.addEventListener('change', async function (e) {
        const input = e.target;

        if (!input?.name) return;

        const side = getSideFromInputName(input.name);
        if (!side) return;

        const file = input.files?.[0];
        if (!file || !file.type.startsWith('image/')) return;

        // Find the companion row container
        const companionRow = input.closest('[data-companion-row]');
        if (!companionRow) return;

        // Show loading indicator
        const ocrStatus = companionRow.querySelector('[data-ocr-status]') || 
                         createOCRStatus(companionRow, input);
        ocrStatus.innerHTML = '<span class="text-sm text-blue-600">Reading ' + side + ' side...</span>';
        ocrStatus.style.display = 'block';

        try {
            const worker = await getWorker();
            const recognitionResult = await worker.recognize(file);
            const extractedText = recognitionResult?.data?.text || '';

            if (extractedText.trim().length <= 10) {
                ocrStatus.innerHTML = '<span class="text-sm text-orange-600">Image text is too unclear</span>';

                return;
            }

            const response = await fetch('{{ route("ocr.parse-text") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ text: extractedText, side }),
            });

            if (!response.ok) {
                throw new Error('OCR parser request failed with status ' + response.status);
            }

            const result = await response.json();

            if (result.success && result.data) {
                fillCompanionFields(companionRow, result.data, side);
                ocrStatus.innerHTML = '<span class="text-sm text-green-600">CNIC ' + side + ' side extracted</span>';

                setTimeout(() => {
                    ocrStatus.style.display = 'none';
                }, 2500);
            } else {
                ocrStatus.innerHTML = '<span class="text-sm text-orange-600">Could not parse CNIC text</span>';
            }
        } catch (error) {
            console.error('Error processing image:', error);
            ocrStatus.innerHTML = '<span class="text-sm text-red-600">OCR failed for this image</span>';
        }
    }, true);

    function createOCRStatus(row, input) {
        const status = document.createElement('div');
        status.setAttribute('data-ocr-status', '');
        status.className = 'mt-2 mb-2';
        status.style.display = 'none';
        input.parentElement.appendChild(status);
        return status;
    }

    function fillCompanionFields(row, data, side) {
        const nameInput = row.querySelector('input[name$="[name]"]');
        const fatherInput = row.querySelector('input[name$="[father_name]"]');
        const cnicInput = row.querySelector('input[name$="[cnic]"]');
        const addressInput = row.querySelector('textarea[name$="[address]"]');

        if (side === 'front') {
            if (data.name && nameInput && !nameInput.value.trim()) {
                nameInput.value = data.name;
                nameInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (data.father_name && fatherInput && !fatherInput.value.trim()) {
                fatherInput.value = data.father_name;
                fatherInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (data.cnic && cnicInput && !cnicInput.value.trim()) {
                cnicInput.value = data.cnic;
                cnicInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        if (side === 'back') {
            if (data.address && addressInput && !addressInput.value.trim()) {
                addressInput.value = data.address;
                addressInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function () {
        if (ocrWorker) {
            ocrWorker.terminate();
            ocrWorker = null;
        }
    });
});
</script>
@endonce
