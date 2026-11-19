import $ from './jquery';
import QRCode from 'qrcode';

// Render the saved invoice and patient ID as a compact, printable QR code.
function renderInvoiceQrCodes() {
    $('.app-clinical-invoice-qr').each(function () {
        const canvas = this;
        const value = $(canvas).attr('data-qr-value');

        if (!value) {
            $(canvas).closest('.app-clinical-invoice-document-mark').prop('hidden', true);
            return;
        }

        QRCode.toCanvas(canvas, value, {
            errorCorrectionLevel: 'M',
            margin: 0,
            width: 110,
            color: {
                dark: '#12396d',
                light: '#ffffff',
            },
        }).catch(() => {
            $(canvas).closest('.app-clinical-invoice-document-mark').prop('hidden', true);
        });
    });
}

$(renderInvoiceQrCodes);
