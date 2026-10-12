{{-- Shared styles of all calibration certificates (wrap the certificate in <div class="pgx page_in">) --}}
@once
<style>
	.pgx { color: #000; --dark: #0b3041; --mid: #104862; --g15: #d9d9d9; --g25: #bfbfbf; }
	.pgx table { width: 100%; border-collapse: collapse; table-layout: fixed; }
	.pgx .blk { border: 2px solid #000; margin-top: 3px; }
	.pgx .blk td { border: 1px solid #000; padding: 0 4px; vertical-align: middle; word-wrap: break-word; line-height: 1.15; }
	.pgx .nb td, .pgx td.nb { border: 0 !important; }
	.pgx .verdana { font-family: Verdana, Geneva, sans-serif; }
	.pgx .abadi { font-family: Abadi, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; }
	.pgx .tnr { font-family: 'Times New Roman', Times, serif; }
	.pgx .aptos { font-family: 'Aptos Display', Aptos, 'Segoe UI', Arial, sans-serif; }
	.pgx .corsiva { font-family: 'Monotype Corsiva', 'Apple Chancery', 'URW Chancery L', cursive; font-style: italic; }
	.pgx td.c { text-align: center; } .pgx td.l { text-align: left; } .pgx td.r { text-align: right; }
	.pgx .b { font-weight: 700; }
	.pgx .pgx-dark { color: var(--dark); } .pgx .pgx-mid { color: var(--mid); }
	.pgx .g15 { background: var(--g15); } .pgx .g25 { background: var(--g25); }
	.pgx .pgx-yellow { background: #ffff00; } .pgx .pgx-amber { background: #ffc000; }
	.pgx .f14 { font-size: 9.5px; } .pgx .f16 { font-size: 10.9px; } .pgx .f18 { font-size: 12.2px; }
	.pgx .f20 { font-size: 13.6px; } .pgx .f22 { font-size: 15px; } .pgx .f24 { font-size: 16.3px; }
	/* section title row (Excel style 327) */
	.pgx .sec { background: var(--g25); color: var(--dark); font-family: Abadi, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; font-weight: 700; font-size: 13.6px; text-align: center; }
	/* grey label cells */
	.pgx .lab { background: var(--g15); color: var(--dark); font-family: Abadi, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; font-weight: 700; font-size: 12.2px; text-align: left; }
	.pgx .labt { background: var(--g15); color: var(--mid); font-family: 'Times New Roman', Times, serif; font-weight: 700; font-size: 13.6px; text-align: left; }
	.pgx .hd { background: var(--g15); color: var(--mid); font-family: Abadi, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; font-weight: 700; font-size: 13.6px; text-align: center; }
	.pgx .rh { background: var(--g15); color: var(--dark); font-family: 'Aptos Display', Aptos, 'Segoe UI', Arial, sans-serif; font-weight: 700; font-size: 13.6px; text-align: center; line-height: 1.2; }
	.pgx .val { font-family: Verdana, Geneva, sans-serif; font-size: 13.6px; text-align: center; }
	.pgx .logos { display: flex; align-items: flex-start; gap: 10px; height: 100%; }
	.pgx .logos img.first { height: 196px; width: auto; }
	.pgx .logos img.rig { height: 140px; width: auto; margin-top: 6px; }
	/* stamp + QR wait for the PDF upload: hidden on screen, revealed in the captured PDF (see paper-v2 take_snap_shot) */
	.pgx .stamp-on-upload { visibility: hidden; }
	.pgx .stamp-pending-note { position: absolute; left: 6px; right: 6px; top: 50%; transform: translateY(-50%); font-family: Verdana, Geneva, sans-serif; font-size: 11px; color: #6b6f82; font-style: italic; }
</style>
@endonce
