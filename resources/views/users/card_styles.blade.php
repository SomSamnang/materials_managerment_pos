<style>
    /* =========================
       PREMIUM ID CARD DESIGN
       ========================= */
    .id-card-pair-wrapper {
        display: flex;
        gap: 30px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .id-card {
        width: 320px;
        height: 540px;
        background: #f8f9fa;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        transition: all .4s ease;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .id-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.25);
    }

    /* Background Pattern */
    .id-card-bg-pattern {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(0,0,0,0.02) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(0,0,0,0.02) 0%, transparent 20%);
        background-color: #ffffff;
        z-index: 0;
    }

    /* Chip Decoration */
    .id-card-chip {
        position: absolute;
        top: 145px;
        left: 30px;
        width: 45px;
        height: 32px;
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        border-radius: 6px;
        z-index: 4;
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: inset 0 1px 2px rgba(255,255,255,0.8), 0 2px 4px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .id-card-chip::before, .id-card-chip::after {
        content: '';
        position: absolute;
        background: rgba(0,0,0,0.1);
    }
    .id-card-chip::before { top: 50%; left: 0; right: 0; height: 1px; }
    .id-card-chip::after { left: 50%; top: 0; bottom: 0; width: 1px; }

    /* ================= HEADER ================= */
    .id-card-header {
        height: 125px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 25px;
        border-radius: 0 0 50% 50% / 0 0 30px 30px;
        position: relative;
        z-index: 2;
    }

    .id-card-logo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(5px);
        border-radius: 12px;
        padding: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* ================= AVATAR ================= */
    .id-card-avatar {
        width: 85px;
        height: 85px;
        border-radius: 50%;
        margin: -45px auto 8px;
        border: 6px solid #fff;
        background: #fff;
        overflow: hidden;
        z-index: 3;
        position: relative;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .id-card-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ================= BODY ================= */
    .id-card-body {
        padding: 0 25px;
        text-align: center;
        position: relative;
        z-index: 3;
        flex-grow: 1;
    }

    .id-card-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .id-card-role {
        font-size: 0.75rem;
        padding: 5px 14px;
        border-radius: 6px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-weight: 700;
        display: inline-block;
    }

    /* ================= INFO ================= */
    .id-card-info {
        margin-top: 10px;
        text-align: left;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 6px 10px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.02);
    }

    .info-icon {
        width: 28px;
        height: 28px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    /* ================= FOOTER ================= */
    .id-card-footer {
        padding: 15px;
        text-align: center;
        position: relative;
        z-index: 3;
        margin-top: auto;
    }

    .barcode {
        height: 30px;
        width: 80%;
        margin: 0 auto;
        background: repeating-linear-gradient(90deg,#334155 0,#334155 2px,transparent 2px,transparent 5px);
        opacity: .5;
    }

    /* ================= PRINT ================= */
    @media print {
        @page { size: auto; margin: 0mm; }
        body * { visibility: hidden; }
        
        /* Modal Print */
        body.modal-open #printCardModal, body.modal-open #printCardModal * { visibility: visible; }
        body.modal-open #printCardModal { position: absolute; left: 0; top: 0; width: 100%; height: 100%; background: white; z-index: 9999; display: flex; align-items: center; justify-content: center; }
        body.modal-open .modal-dialog { width: 100%; max-width: none; margin: 0; }
        body.modal-open .modal-content { border: none; box-shadow: none; }
        body.modal-open .modal-header, body.modal-open .modal-footer, body.modal-open .btn-close { display: none; }

        /* Bulk Print */
        body.printing-all-cards #printable-cards, body.printing-all-cards #printable-cards * { visibility: visible; }
        body.printing-all-cards #printable-cards { position: absolute; left: 0; top: 0; width: 100%; background: white; z-index: 9999; padding: 20px; display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }

        .id-card { box-shadow: none !important; border: 1px solid #ddd; page-break-inside: avoid; break-inside: avoid; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .id-card-pair-wrapper { margin-bottom: 20px; page-break-inside: avoid; break-inside: avoid; }
    }
</style>