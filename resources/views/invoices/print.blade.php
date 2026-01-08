<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>វិក្កយបត្រ - {{ $invoice->invoice_number }}</title>
    <style>
        @page { size: 8.5in 11in; }
        @media print {
            @page { size: 8.5in 11in; }
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            .button-container { display: none !important; }
            .print-button { display: none !important; }
            .preview-button { display: none !important; }
        }

        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #2c3e50;
            margin: 0.2in; /* 0.2 inch margins for vertical paper */
            padding: 0;
            background: white;
        }

        .invoice-container {
            width: 7in; /* Optimized for 8.5in vertical paper with margins */
            margin: 0 auto;
            padding: 0.3in; /* 0.3 inch padding on all sides */
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e3e6ea;
        }

        .header {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 15px;
            border-radius: 6px 6px 0 0;
            margin: -0.3in -0.3in 20px -0.3in;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            border-radius: 6px 6px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .header p {
            margin: 4px 0;
            font-size: 12px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .company-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 10px;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
        }

        .info-section {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px;
        }

        .info-section h3 {
            margin: 0 0 6px 0;
            font-size: 12px;
            color: #495057;
            border-bottom: 2px solid #667eea;
            padding-bottom: 3px;
            font-weight: 600;
        }

        .info-section p {
            margin: 3px 0;
            color: #6c757d;
            font-size: 10px;
        }

        .info-section p strong {
            color: #2c3e50;
        }

        .invoice-details {
            margin-bottom: 15px;
        }

        .invoice-details h3 {
            color: #495057;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 600;
            border-left: 3px solid #667eea;
            padding-left: 8px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            font-size: 10px;
        }

        .invoice-details th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .invoice-details td {
            border: 1px solid #e3e6ea;
            padding: 4px;
            background: white;
            color: #495057;
        }

        .invoice-details tr:nth-child(even) {
            background: #f8f9fa;
        }

        .invoice-details tr:hover {
            background: #e3f2fd;
            transition: background-color 0.2s ease;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 2px solid #667eea;
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 4px;
            align-items: center;
        }

        .total-row:last-child {
            margin-bottom: 0;
        }

        .total-label {
            font-weight: 600;
            min-width: 130px;
            color: #495057;
            font-size: 11px;
        }

        .total-row strong .total-label {
            color: #2c3e50;
            font-size: 12px;
        }

        .total-row span:last-child {
            font-weight: 600;
            color: #667eea;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-unpaid {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #cce5ff;
            color: #004085;
        }

        .status-overdue {
            background: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 8px;
        }

        .footer p {
            margin: 3px 0;
        }

        .print-button {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .button-container {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .preview-button {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        .preview-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
    </style>
</head>
<body>
    <div class="button-container no-print">
        <button class="preview-button" onclick="printPreview()">👁️ មើលជាមុន</button>
        <button class="print-button" onclick="window.print()">🖨️ បោះពុម្ព</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <h1>វិក្កយបត្រ</h1>
            <p>ប្រព័ន្ធគ្រប់គ្រងសម្ភារៈ</p>
            <p>ទំនាក់ទំនង: +855 12 345 678 | Email: info@material-system.com</p>
        </div>

        <div class="company-info">
            <p><strong>ក្រុមហ៊ុន:</strong> Material System Co., Ltd</p>
            <p><strong>អាសយដ្ឋាន:</strong> Phnom Penh, Cambodia</p>
            <p><strong>លេខទូរស័ព្ទ:</strong> +855 12 345 678 | <strong>អ៊ីមែល:</strong> info@material-system.com</p>
        </div>

    <div class="invoice-info">
        <div class="info-section">
            <h3>ព័ត៌មានវិក្កយបត្រ</h3>
            <p><strong>លេខវិក្កយបត្រ:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>ថ្ងៃចេញវិក្កយបត្រ:</strong> {{ $invoice->issued_date->format('d/m/Y') }}</p>
            <p><strong>ថ្ងៃផុតកំណត់:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : 'N/A' }}</p>
      
        </div>

        <div class="info-section">
            <h3>ព័ត៌មានអ្នកបញ្ជាទិញ</h3>
            <p><strong>ឈ្មោះ:</strong> {{ $invoice->order->user->name }}</p>
            <p><strong>អ៊ីមែល:</strong> {{ $invoice->order->user->email }}</p>
            <p><strong>តួនាទី:</strong> {{ ucfirst($invoice->order->user->role) }}</p>
        </div>
    </div>

    <div class="invoice-details">
        <h3>ព័ត៌មានអ្នកបញ្ជាទិញ</h3>
        <table>
            <thead>
                <tr>
                    <th class="text-center">ល.រ</th>
                    <th>ឈ្មោះសម្ភារៈ</th>
                    <th>កូដ</th>
                    <th class="text-center">ចំនួន</th>
                    <th class="text-right">តម្លៃឯកតា ($)</th>
                    <th class="text-right">តម្លៃសរុប ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->materials as $index => $material)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $material->name }}</td>
                    <td>{{ $material->code }}</td>
                    <td class="text-center">{{ $material->pivot->quantity }}</td>
                    <td class="text-right">${{ number_format($material->pivot->unit_price_usd, 2) }}</td>
                    <td class="text-right">${{ number_format($material->pivot->quantity * $material->pivot->unit_price_usd, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">តម្លៃសរុប (USD):</span>
            <span>${{ number_format($invoice->total_amount_usd, 2) }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">តម្លៃសរុប (KHR):</span>
            <span>{{ number_format($invoice->total_amount_khr) }} ៛</span>
        </div>
     
    </div>

    <div class="footer">
        <p>សូមបង់ប្រាក់ទៅគណនី: ACB - 1234567890 - Material System Co., Ltd</p>
        <p>ថ្ងៃបោះពុម្ព: {{ now()->format('d/m/Y H:i') }}</p>
        <p>សូមអរគុណសម្រាប់ការប្រើប្រាស់សេវាកម្មរបស់យើង។</p>
    </div>
    </div>

    <script>
        function printPreview() {
            // Create a new window for print preview
            var printWindow = window.open('', '_blank', 'width=800,height=600');
            
            // Copy the current document content
            var content = document.documentElement.outerHTML;
            
            // Remove the button container from preview (so it doesn't show buttons in preview)
            content = content.replace(/<div class="button-container no-print">[\s\S]*?<\/div>/, '');
            
            // Write content to new window
            printWindow.document.write(content);
            printWindow.document.close();
            
            // Add title to preview window
            printWindow.document.title = 'វិក្កយបត្រ - Print Preview';
            
            // Focus the preview window
            printWindow.focus();
            
            // Optional: Auto-open print dialog after a short delay
            setTimeout(function() {
                printWindow.print();
            }, 500);
        }

        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>