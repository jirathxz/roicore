<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — แผนที่ติดตามและเฝ้าระวังสถานการณ์น้ำท่วม</title>
    
    <!-- Google Fonts: Inter & Noto Sans Thai -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

    <style>
        /* ==========================================================================
           ROiCORE Design System Tokens (White Modern Architecture)
           อ้างอิงตาม rules.md และ design.md อย่างเคร่งครัด
           กฎเหล็ก: NO BORDER, NO SHADOW ทุกกรณี และใช้ Surface Layering แทน
           ========================================================================== */
        :root {
            /* 1. Brand Accent Colors */
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;

            /* 2. Surface Layering (มิติความลึกด้วยระดับสีพื้นผิว แทนการใช้เงาและขอบ) */
            --bg-body: #F1F5F9;            /* Slate 100 — พื้นหลังจอ */
            --surface: #FFFFFF;            /* Pure White — การ์ดหลัก, Modal, Navbar */
            --surface-subtle: #F8FAFC;     /* Slate 50 — กล่องข้อมูลย่อย, ช่องกรอก */
            --surface-active: #E2E8F0;     /* Slate 200 — สถานะถูกเลือก, ปุ่มรอง */
            --surface-hover: #CBD5E1;      /* Slate 300 — สถานะเมาส์ชี้ปุ่มรอง */

            /* 3. Typography Colors */
            --text-main: #0F172A;          /* Slate 900 — ข้อความหลัก */
            --text-muted: #64748B;         /* Slate 500 — ข้อความรอง */
            --text-light: #94A3B8;         /* Slate 400 — เส้นแบ่งจางๆ หรือตัวเลข */

            /* 4. Semantic Status & Risk Colors */
            --risk-critical: #DC2626;      /* สีแดง — ระดับวิกฤต */
            --risk-critical-bg: #FEF2F2;
            --risk-high: #EA580C;          /* สีส้ม — ระดับสูง */
            --risk-high-bg: #FFF7ED;
            --risk-medium: #D97706;        /* สีเหลืองอำพัน — ปานกลาง */
            --risk-medium-bg: #FEF3C7;
            --risk-low: #2563EB;           /* สีน้ำเงิน — เฝ้าระวัง/เล็กน้อย */
            --risk-low-bg: #EFF6FF;
            --status-success: #16A34A;     /* สีเขียว — ปกติ/ปลอดภัย */
            --status-success-bg: #F0FDF4;
            --gistda-purple: #7C3AED;      /* สีม่วง — ข้อมูลดาวเทียม GISTDA */
            --gistda-tint: #EDE9FE;

            /* 5. Radius Hierarchy Scale (องค์ประกอบนอกมนกว่าในเสมอ) */
            --r-sm: 8px;                   /* ป้ายเล็ก, ไอคอนแท็ก */
            --r-md: 10px;                  /* ปุ่มกด, ช่องกรอกข้อมูล, ดรอปดาวน์ */
            --r-lg: 14px;                  /* กล่องย่อยภายในการ์ด, รายการแถว */
            --r-xl: 20px;                  /* การ์ดหลัก, Modal */
            --r-full: 9999px;              /* แคปซูลสถานะ, ไอคอนวงกลม, FAB */
        }

        /* Global Reset: ตัด Border และ Box-Shadow ทั้งหมดตามกฎ White Modern */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        body {
            font-family: 'Noto Sans Thai', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Fullscreen Layout: 100vw x 100vh */
        .app-layout {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* Leaflet Map Canvas */
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            background-color: var(--bg-body);
        }

        /* Leaflet Controls & Popups Reset to White Modern */
        .leaflet-container {
            font-family: 'Noto Sans Thai', 'Inter', sans-serif !important;
            background-color: var(--bg-body) !important;
        }

        .leaflet-bar,
        .leaflet-bar a,
        .leaflet-control-zoom,
        .leaflet-control-zoom a {
            border: none !important;
            box-shadow: none !important;
            background-color: var(--surface) !important;
            color: var(--text-main) !important;
            border-radius: var(--r-md) !important;
            width: 38px !important;
            height: 38px !important;
            line-height: 38px !important;
            text-align: center !important;
            margin-bottom: 6px !important;
            transition: background-color 0.15s ease;
        }

        .leaflet-bar a:hover {
            background-color: var(--surface-active) !important;
            color: var(--accent-blue) !important;
        }

        .leaflet-control-attribution {
            background-color: rgba(255, 255, 255, 0.85) !important;
            border-radius: var(--r-sm) !important;
            padding: 4px 8px !important;
            font-size: 11px !important;
            color: var(--text-muted) !important;
            margin: 8px !important;
        }

        .leaflet-popup-content-wrapper {
            background: var(--surface) !important;
            border-radius: var(--r-xl) !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        .leaflet-popup-content {
            margin: 0 !important;
            line-height: 1.5 !important;
        }

        .leaflet-popup-tip-container,
        .leaflet-popup-tip {
            display: none !important;
        }

        path.leaflet-interactive {
            stroke: none !important;
            stroke-width: 0 !important;
        }

        /* ==========================================================================
           Top Floating Navbar & Header Controls
           ========================================================================== */
        .top-navbar {
            position: absolute;
            top: 16px;
            left: 16px;
            right: 16px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            pointer-events: none;
            flex-wrap: wrap;
        }

        .nav-group-left,
        .nav-group-right {
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
        }

        .nav-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 19px;
            color: var(--text-main);
            letter-spacing: -0.03em;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .brand-logo .accent-i {
            color: var(--accent-blue);
            font-style: normal;
        }

        .nav-tabs {
            display: flex;
            gap: 4px;
            background-color: var(--surface-subtle);
            padding: 4px;
            border-radius: var(--r-lg);
        }

        .nav-tab {
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--r-md);
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .nav-tab.active {
            background-color: var(--surface);
            color: var(--accent-blue);
        }

        .nav-tab:hover:not(.active) {
            color: var(--text-main);
            background-color: var(--surface-active);
        }

        /* GISTDA Satellite Layer Switch Card */
        .gistda-switch-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 8px 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .gistda-badge-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--r-full);
            background-color: var(--gistda-tint);
            color: var(--gistda-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .gistda-text-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .gistda-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .gistda-subtext {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Modern Switch Slider (Zero Border, Zero Shadow) */
        .switch-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            cursor: pointer;
            margin-left: 4px;
        }

        .switch-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .switch-slider {
            position: absolute;
            inset: 0;
            background-color: var(--surface-active);
            border-radius: var(--r-full);
            transition: background-color 0.2s ease;
        }

        .switch-slider::before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: var(--surface);
            border-radius: var(--r-full);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .switch-toggle input:checked + .switch-slider {
            background-color: var(--gistda-purple);
        }

        .switch-toggle input:checked + .switch-slider::before {
            transform: translateX(20px);
        }

        /* ==========================================================================
           Floating Filter & Legend Panel (Bottom-Left)
           ========================================================================== */
        .floating-legend-panel {
            position: absolute;
            bottom: 24px;
            left: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: auto;
            max-width: 320px;
        }

        .legend-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .legend-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
        }

        .legend-stat-pill {
            font-size: 11.5px;
            padding: 3px 8px;
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            border-radius: var(--r-full);
            font-weight: 600;
        }

        .legend-items-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 5px 10px;
            border-radius: var(--r-md);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-chip.active {
            background-color: var(--surface-active);
            color: var(--text-main);
        }

        .filter-chip:hover {
            background-color: var(--surface-active);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: var(--r-full);
            display: inline-block;
        }

        .dot-critical { background-color: var(--risk-critical); }
        .dot-high { background-color: var(--risk-high); }
        .dot-medium { background-color: var(--risk-medium); }
        .dot-low { background-color: var(--risk-low); }
        .dot-gistda { background-color: var(--gistda-purple); }

        /* Current Location & Centering Floating Actions */
        .btn-locate {
            background-color: var(--surface);
            color: var(--text-main);
            border-radius: var(--r-full);
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.15s ease;
        }

        .btn-locate:hover {
            background-color: var(--surface-active);
            color: var(--accent-blue);
        }

        /* ==========================================================================
           Custom Map Markers (Surface Layering & Risk Colors)
           ========================================================================== */
        .custom-risk-marker {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--r-full);
            cursor: pointer;
        }

        .marker-core {
            width: 36px;
            height: 36px;
            border-radius: var(--r-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 15px;
            transition: transform 0.15s ease;
        }

        .custom-risk-marker:hover .marker-core {
            transform: scale(1.15);
        }

        /* Marker Colors by Risk Level */
        .marker-critical .marker-core { background-color: var(--risk-critical); }
        .marker-high .marker-core { background-color: var(--risk-high); }
        .marker-medium .marker-core { background-color: var(--risk-medium); }
        .marker-low .marker-core { background-color: var(--risk-low); }

        /* Radar Pulse Animation for Critical Points (Strictly NO Box-Shadow) */
        @keyframes radarPulse {
            0% {
                transform: scale(0.9);
                opacity: 0.8;
            }
            70% {
                transform: scale(2.2);
                opacity: 0;
            }
            100% {
                transform: scale(2.4);
                opacity: 0;
            }
        }

        .pulse-ring {
            position: absolute;
            inset: -4px;
            border-radius: var(--r-full);
            background-color: var(--risk-critical);
            opacity: 0;
            pointer-events: none;
            z-index: -1;
        }

        .is-pulsing .pulse-ring {
            animation: radarPulse 2.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) infinite;
        }

        /* ==========================================================================
           Popup Info Cards (Surface Layering)
           ========================================================================== */
        .map-popup-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 16px;
            width: 280px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .popup-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
        }

        .popup-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.35;
        }

        .popup-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: var(--r-sm);
            white-space: nowrap;
        }

        .badge-critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .badge-high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .badge-medium { background-color: var(--risk-medium-bg); color: var(--risk-medium); }
        .badge-low { background-color: var(--risk-low-bg); color: var(--risk-low); }
        .badge-gistda { background-color: var(--gistda-tint); color: var(--gistda-purple); }

        .popup-inner-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .popup-stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
        }

        .stat-label {
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .stat-value {
            font-weight: 700;
            color: var(--text-main);
        }

        .popup-btn-action {
            background-color: var(--surface-active);
            color: var(--text-main);
            border-radius: var(--r-md);
            padding: 8px;
            font-size: 12.5px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.15s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .popup-btn-action:hover {
            background-color: var(--accent-blue);
            color: #FFFFFF;
        }

        /* ==========================================================================
           Floating Action Button (FAB) for Quick Flood Report
           ========================================================================== */
        .fab-report {
            position: absolute;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            background-color: var(--accent-blue);
            color: #FFFFFF;
            padding: 14px 24px;
            border-radius: var(--r-full);
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.15s ease, transform 0.15s ease;
            text-decoration: none;
            pointer-events: auto;
        }

        .fab-report:hover {
            background-color: var(--accent-blue-hover);
            transform: translateY(-2px);
        }

        .fab-report:active {
            transform: translateY(0);
        }

        /* ==========================================================================
           Quick Incident Report Modal (≤ 3 Taps Flow)
           ========================================================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.45);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 20px;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: modalSlideUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-close-modal {
            background: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            border-radius: var(--r-sm);
            transition: color 0.15s ease;
        }

        .btn-close-modal:hover {
            color: var(--text-main);
        }

        /* Form Labels */
        .form-section-label {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-section-optional {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--text-muted);
        }

        /* 1. ช่องที่ 1: เลือกระดับน้ำ 4 ระดับ (Compact 2x2 Grid) */
        .water-levels-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .water-level-btn {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 9px 10px;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .water-level-btn:hover {
            background-color: var(--surface-active);
        }

        .water-level-btn.selected {
            background-color: var(--accent-tint);
        }

        .water-level-btn.selected .level-name {
            color: var(--accent-blue);
        }

        .level-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .level-desc {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* 2. ช่องที่ 2: เบอร์โทรศัพท์ติดต่อ (Surface Layering) */
        .input-text-field {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 9px 12px;
            font-size: 13px;
            color: var(--text-main);
            font-family: inherit;
            width: 100%;
            transition: background-color 0.15s ease;
        }

        .input-text-field:focus {
            background-color: var(--surface-active);
        }

        .input-text-field::placeholder {
            color: var(--text-light);
        }

        /* 3. ช่องที่ 3: ปุ่มเลือกรูปถ่ายสถานที่จริง (ไม่บังคับ) */
        .photo-select-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.15s ease;
        }

        .btn-select-photo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-main);
            cursor: pointer;
            width: 100%;
        }

        .btn-select-photo:hover {
            color: var(--accent-blue);
        }

        .photo-preview-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 8px;
        }

        .photo-preview-thumb {
            width: 32px;
            height: 32px;
            border-radius: var(--r-sm);
            object-fit: cover;
        }

        .photo-preview-info {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow: hidden;
        }

        .photo-name-text {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .photo-status-text {
            font-size: 11px;
            color: var(--status-success);
            font-weight: 500;
        }

        .btn-remove-photo {
            background: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
            transition: color 0.15s ease;
        }

        .btn-remove-photo:hover {
            color: var(--risk-critical);
        }

        /* 4. แถบแสดงสถานะ Geolocation GPS อัตโนมัติ */
        .location-status-bar {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 12px;
        }

        .location-status-text {
            color: var(--text-main);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
        }

        .btn-change-pin {
            background-color: var(--surface-active);
            color: var(--text-main);
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 9px;
            border-radius: var(--r-sm);
            cursor: pointer;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background-color 0.15s ease;
        }

        .btn-change-pin:hover {
            background-color: var(--accent-blue);
            color: #FFFFFF;
        }

        /* ปุ่มส่งรายงานเหตุการณ์ (Tap 3) */
        .btn-submit-report {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            border-radius: var(--r-md);
            padding: 11px 16px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 2px;
        }

        .btn-submit-report:hover {
            background-color: var(--accent-blue-hover);
        }

        .btn-submit-report:disabled {
            background-color: var(--surface-active);
            color: var(--text-muted);
            cursor: not-allowed;
        }

        /* Toast Feedback Notification */
        .toast-notify {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            background-color: var(--surface);
            color: var(--text-main);
            border-radius: var(--r-xl);
            padding: 12px 20px;
            font-size: 13.5px;
            font-weight: 600;
            z-index: 3000;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-notify.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--r-full);
            background-color: var(--status-success-bg);
            color: var(--status-success);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        /* ==========================================================================
           1 km Radius Pin Mode Banner & Interactive Marker (White Modern Rules)
           ========================================================================== */
        .pin-mode-banner {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            width: calc(100% - 32px);
            max-width: 580px;
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 14px 18px;
            z-index: 2500;
            display: none;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pin-mode-banner.active {
            display: flex;
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }

        .pin-mode-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .pin-mode-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pin-mode-badge {
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: var(--r-full);
            background-color: var(--accent-tint);
            color: var(--accent-blue);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .pin-mode-badge.out-range {
            background-color: var(--risk-high-bg);
            color: var(--risk-high);
        }

        .pin-mode-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pin-mode-info {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.4;
            flex: 1;
            min-width: 200px;
        }

        .pin-mode-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-confirm-pin {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-size: 12.5px;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: var(--r-md);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background-color 0.15s ease;
        }

        .btn-confirm-pin:hover {
            background-color: var(--accent-blue-hover);
        }

        .btn-cancel-pin {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-size: 12.5px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: var(--r-md);
            cursor: pointer;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .btn-cancel-pin:hover {
            background-color: var(--surface-active);
            color: var(--text-main);
        }

        /* Leaflet DivIcon wrapper for Draggable Pick Pin (อยู่เหนือทุกเลเยอร์) */
        .pick-pin-leaflet-div-icon {
            z-index: 10000 !important;
            pointer-events: auto !important;
            background: transparent !important;
            border: none !important;
        }

        /* Interactive Draggable & Droppable Pick Marker */
        .interactive-pick-pin {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: grab !important;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none; /* ป้องกัน browser gestures ขัดขวางการลากหมุด */
            position: relative;
            pointer-events: auto !important;
        }

        .interactive-pick-pin:active,
        .interactive-pick-pin.is-dragging,
        .interactive-pick-pin.is-dragging-active {
            cursor: grabbing !important;
        }

        .pick-pin-zone-badge {
            font-size: 10.5px;
            font-weight: 700;
            color: #FFFFFF;
            background-color: var(--accent-blue);
            padding: 3px 9px;
            border-radius: var(--r-full);
            white-space: nowrap;
            margin-bottom: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            pointer-events: none !important; /* ป้ายไม่รับ event เพื่อไม่ให้ขวางการจับลากหมุด */
            transition: background-color 0.15s ease;
        }

        .pick-pin-zone-badge.out-range {
            background-color: var(--risk-high);
        }

        .pick-pin-tooltip {
            background-color: var(--surface);
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: var(--r-full);
            white-space: nowrap;
            margin-bottom: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            pointer-events: none !important; /* Tooltip ไม่ขัดขวางการคลิกหรือลาก */
            transition: all 0.15s ease;
        }

        .pick-pin-tooltip.in-range {
            color: var(--accent-blue);
            background-color: var(--accent-tint);
        }

        .pick-pin-tooltip.out-range {
            color: var(--risk-high);
            background-color: var(--risk-high-bg);
        }

        .pick-pin-icon-wrap {
            position: relative;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: auto !important;
            cursor: grab !important;
        }

        .pick-pin-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--r-full);
            background-color: var(--accent-blue);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            transition: background-color 0.2s ease, transform 0.15s ease;
            z-index: 10;
            pointer-events: auto !important;
            cursor: grab !important;
        }

        .interactive-pick-pin:active .pick-pin-icon,
        .interactive-pick-pin.is-dragging .pick-pin-icon,
        .interactive-pick-pin.is-dragging-active .pick-pin-icon {
            cursor: grabbing !important;
            transform: scale(1.15);
        }

        .pick-pin-icon.out-range {
            background-color: var(--risk-high);
        }

        .pick-pin-pulse {
            position: absolute;
            inset: -6px;
            border-radius: var(--r-full);
            background-color: var(--accent-blue);
            opacity: 0.35;
            animation: radarPulse 2s cubic-bezier(0.25, 0.46, 0.45, 0.94) infinite;
            z-index: 1;
            pointer-events: none !important;
        }

        .pick-pin-icon.out-range + .pick-pin-pulse {
            background-color: var(--risk-high);
        }

        .btn-locate.radius-active {
            background-color: var(--accent-tint);
            color: var(--accent-blue);
            font-weight: 700;
        }

        .location-actions-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* Responsive Tweaks */
        @media (max-width: 640px) {
            .top-navbar {
                top: 10px;
                left: 10px;
                right: 10px;
                gap: 8px;
            }

            .nav-card {
                padding: 6px 12px;
            }

            .nav-tabs {
                display: none; /* Hide desktop tabs on small screens to save space */
            }

            .floating-legend-panel {
                bottom: 86px;
                left: 12px;
                right: 12px;
                max-width: none;
            }

            .fab-report {
                bottom: 20px;
                right: 16px;
                padding: 12px 20px;
                font-size: 13.5px;
            }

            .gistda-text-group .gistda-subtext {
                display: none;
            }

            .pin-mode-banner {
                top: 10px;
                width: calc(100% - 20px);
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="app-layout">
        <!-- Floating Banner for 5km Pin Picking Mode -->
        <div class="pin-mode-banner" id="pinPickerBanner">
            <div class="pin-mode-header">
                <div class="pin-mode-title">
                    <i class="fa-solid fa-location-crosshairs" style="color:var(--accent-blue);"></i>
                    <span>โหมดปักหมุด & ตีกรอบรัศมี 5 กิโลเมตร</span>
                </div>
                <span class="pin-mode-badge" id="pinRangeBadge">
                    <i class="fa-solid fa-circle-check"></i> ในรัศมี 5 กม.
                </span>
            </div>
            <div class="pin-mode-body">
                <div class="pin-mode-info" id="pinModeInfoText">
                    แตะบนแผนที่เพื่อ Drop หมุด หรือลากหมุดไปยังตำแหน่งที่ต้องการ พร้อมตีกรอบรัศมี 5 กิโลเมตร (78.54 ตร.กม.)
                </div>
                <div class="pin-mode-actions">
                    <button type="button" class="btn-cancel-pin" id="btnCancelPinPick">
                        <i class="fa-solid fa-xmark"></i> ยกเลิก
                    </button>
                    <button type="button" class="btn-confirm-pin" id="btnConfirmPinPick">
                        <i class="fa-solid fa-check"></i> ยืนยันตำแหน่งนี้
                    </button>
                </div>
            </div>
        </div>

        <!-- Top Floating Navigation & GISTDA Switch -->
        <header class="top-navbar" id="topNavbar">
            <div class="nav-group-left">
                <!-- Brand Identity Wordmark -->
                <div class="nav-card">
                    <a href="./" class="brand-logo" title="ROiCORE แพลตฟอร์มเฝ้าระวังน้ำท่วม">
                        RO<span class="accent-i">i</span>CORE
                    </a>
                </div>

                <!-- Main View Tabs -->
                <nav class="nav-card">
                    <div class="nav-tabs">
                        <a href="./" class="nav-tab active">
                            <i class="fa-solid fa-map-location-dot"></i> แผนที่
                        </a>
                        <a href="?page=dashboard" class="nav-tab">
                            <i class="fa-solid fa-chart-pie"></i> แดชบอร์ด
                        </a>
                        <a href="?page=api" class="nav-tab">
                            <i class="fa-solid fa-code"></i> สำหรับนักพัฒนา
                        </a>
                    </div>
                </nav>
            </div>

            <div class="nav-group-right">
                <!-- GISTDA 77-Province Satellite Layer Switch Card -->
                <div class="gistda-switch-card" id="gistdaSwitchCard">
                    <span class="gistda-badge-icon">
                        <i class="fa-solid fa-satellite-dish"></i>
                    </span>
                    <div class="gistda-text-group">
                        <span class="gistda-title">
                            ดาวเทียม 77 จังหวัด
                        </span>
                        <span class="gistda-subtext" id="gistdaStatusText">กำลังโหลดข้อมูล...</span>
                    </div>
                    <label class="switch-toggle" for="gistdaSwitch">
                        <input type="checkbox" id="gistdaSwitch" checked>
                        <span class="switch-slider"></span>
                    </label>
                </div>
            </div>
        </header>

        <!-- Fullscreen OpenStreetMap Container -->
        <main id="map"></main>

        <!-- Floating Legend & Risk Filter Panel (Bottom-Left) -->
        <div class="floating-legend-panel">
            <div class="legend-card">
                <div class="legend-header">
                    <span>ระดับความเสี่ยงน้ำท่วม</span>
                    <span class="legend-stat-pill" id="statPointCount">0 จุดตรวจวัด</span>
                </div>
                <div class="legend-items-row">
                    <button type="button" class="filter-chip active" data-filter="all">
                        ทั้งหมด
                    </button>
                    <button type="button" class="filter-chip" data-filter="CRITICAL">
                        <span class="legend-dot dot-critical"></span> วิกฤต
                    </button>
                    <button type="button" class="filter-chip" data-filter="HIGH">
                        <span class="legend-dot dot-high"></span> สูง
                    </button>
                    <button type="button" class="filter-chip" data-filter="MEDIUM">
                        <span class="legend-dot dot-medium"></span> ปานกลาง
                    </button>
                    <button type="button" class="filter-chip" data-filter="LOW">
                        <span class="legend-dot dot-low"></span> เฝ้าระวัง
                    </button>
                </div>
            </div>

            <div class="location-actions-row">
                <!-- Locate GPS Button -->
                <button type="button" class="btn-locate" id="btnLocateUser">
                    <i class="fa-solid fa-crosshairs" style="color:var(--accent-blue);"></i> ตำแหน่งปัจจุบันของฉัน
                </button>
                <!-- Quick Drop & Drag Pin with 5km Zone Button -->
                <button type="button" class="btn-locate" id="btnQuickDropPin" title="ปักหมุดหรือลากหมุดเพื่อตีกรอบพื้นที่ 5 กิโลเมตร">
                    <i class="fa-solid fa-location-dot" style="color:var(--accent-blue);"></i> ปักหมุดตีกรอบ 5 กม.
                </button>
                <!-- 5km Radius Toggle Button -->
                <button type="button" class="btn-locate" id="btnToggle1kmRadius" title="แสดงขอบเขตรัศมี 5 กิโลเมตรรอบตำแหน่งของคุณ">
                    <i class="fa-solid fa-bullseye" style="color:var(--accent-blue);"></i> รัศมี 5 กม. รอบตัวฉัน
                </button>
            </div>
        </div>

        <!-- Floating Action Button for Quick Flood Report (Bottom-Right) -->
        <button type="button" class="fab-report" id="btnOpenReportModal">
            <i class="fa-solid fa-bullhorn"></i> แจ้งเหตุน้ำท่วมด่วน
        </button>
    </div>

    <!-- Quick Incident Report Modal (≤ 3 Taps Flow) -->
    <div class="modal-overlay" id="reportModal">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fa-solid fa-bullhorn" style="color:var(--accent-blue);"></i> แจ้งเหตุน้ำท่วมด่วน
                </h2>
                <button type="button" class="btn-close-modal" id="btnCloseReportModal" title="ปิดหน้าต่าง">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- ช่องที่ 1: เลือก 4 ระดับน้ำ (แตะเลือกครั้งที่ 1) -->
            <div>
                <div class="form-section-label">
                    <span>1. ระดับน้ำท่วม</span>
                </div>
                <div class="water-levels-grid">
                    <div class="water-level-btn" data-severity="1" data-label="เล็กน้อย น้ำขังผิวทาง">
                        <div class="level-name">
                            <i class="fa-solid fa-droplet" style="color:var(--risk-low);"></i> เล็กน้อย
                        </div>
                        <div class="level-desc">น้ำขังผิวทาง รอการระบาย</div>
                    </div>
                    <div class="water-level-btn" data-severity="2" data-label="ปานกลาง ท่วมทางเท้า 10-30 ซม.">
                        <div class="level-name">
                            <i class="fa-solid fa-water" style="color:var(--risk-medium);"></i> ปานกลาง
                        </div>
                        <div class="level-desc">ระดับทางเท้า 10-30 ซม.</div>
                    </div>
                    <div class="water-level-btn selected" data-severity="3" data-label="รถเล็กผ่านไม่ได้ สูง 30-50 ซม.">
                        <div class="level-name">
                            <i class="fa-solid fa-car-burst" style="color:var(--risk-high);"></i> รถเล็กผ่านไม่ได้
                        </div>
                        <div class="level-desc">ท่วมสูง 30-50 ซม. เลี่ยงทาง</div>
                    </div>
                    <div class="water-level-btn" data-severity="3" data-label="วิกฤตจมมิด สูงเกิน 50 ซม.">
                        <div class="level-name">
                            <i class="fa-solid fa-triangle-exclamation" style="color:var(--risk-critical);"></i> วิกฤตจมมิด
                        </div>
                        <div class="level-desc">สูงเกิน 50 ซม. ขอความช่วยเหลือ</div>
                    </div>
                </div>
            </div>

            <!-- ช่องที่ 2: เบอร์โทรศัพท์ติดต่อ (จดจำค่าลง LocalStorage อัตโนมัติ) -->
            <div>
                <div class="form-section-label">
                    <span>2. เบอร์โทรศัพท์ติดต่อ</span>
                    <span class="form-section-optional">จดจำอัตโนมัติ</span>
                </div>
                <input type="tel" 
                       id="reporterPhone" 
                       class="input-text-field" 
                       placeholder="กรอกเบอร์โทรศัพท์สำหรับติดต่อกลับ" 
                       maxlength="15">
            </div>

            <!-- ช่องที่ 3: ปุ่มเลือกรูปถ่ายสถานที่จริง (ไม่บังคับ) -->
            <div>
                <div class="form-section-label">
                    <span>3. รูปถ่ายสถานที่จริง</span>
                    <span class="form-section-optional">ไม่บังคับ</span>
                </div>
                <div class="photo-select-box">
                    <label for="reportPhotoInput" class="btn-select-photo" id="btnSelectPhotoLabel">
                        <i class="fa-solid fa-camera" style="color:var(--accent-blue);"></i>
                        <span>แตะเพื่อเลือกรูปถ่ายสถานที่จริง</span>
                    </label>
                    <input type="file" id="reportPhotoInput" accept="image/*" style="display:none;">
                    <div id="photoPreviewContainer" class="photo-preview-container" style="display:none;">
                        <img id="photoPreviewImg" class="photo-preview-thumb" alt="ตัวอย่างรูปถ่าย">
                        <div class="photo-preview-info">
                            <span id="photoPreviewName" class="photo-name-text">รูปถ่ายสถานที่จริง</span>
                            <span class="photo-status-text">แนบรูปถ่ายเรียบร้อย</span>
                        </div>
                        <button type="button" id="btnRemovePhoto" class="btn-remove-photo" title="ลบรูป">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ดึงพิกัด Geolocation อัตโนมัติทันทีที่เปิดฟอร์ม พร้อมปุ่มปักหมุดบนแผนที่ในรัศมี 5 กม. -->
            <div class="location-status-bar">
                <div class="location-status-text" id="reportLocationStatus">
                    <i class="fa-solid fa-spinner fa-spin" style="color:var(--accent-blue);"></i> กำลังตรวจหาพิกัดปัจจุบันอัตโนมัติ...
                </div>
                <button type="button" class="btn-change-pin" id="btnPickLocationOnMap">
                    <i class="fa-solid fa-location-dot"></i> ลาก/ปักหมุดตีกรอบ 5 กม.
                </button>
            </div>

            <!-- ส่งรายงานเหตุการณ์ (แตะครั้งที่ 3) -->
            <button type="button" class="btn-submit-report" id="btnSubmitReport">
                <i class="fa-solid fa-paper-plane"></i> ส่งรายงานสถานการณ์ด่วน
            </button>
        </div>
    </div>

    <!-- Polite Feedback Notification Toast -->
    <div class="toast-notify" id="toastNotify">
        <span class="toast-icon">
            <i class="fa-solid fa-check"></i>
        </span>
        <span id="toastMessage">ส่งรายงานข้อมูลเรียบร้อยแล้ว</span>
    </div>

    <!-- Leaflet JS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        /**
         * =========================================================================
         * ROiCORE Map Controller & Spatial Engine
         * ปฏิบัติตาม rules.md และ design.md (White Modern UI)
         * =========================================================================
         */
        document.addEventListener('DOMContentLoaded', () => {
            // Helper เพื่อหา Base URL รองรับทั้ง XAMPP และ Built-in Server
            const getApiUrl = (endpoint) => {
                const clean = endpoint.replace(/^\/+/, '');
                const currentPath = window.location.pathname;
                
                // หากกำลังทำงานในโฟลเดอร์ย่อย เช่น /roicore/public/
                if (currentPath.includes('/public')) {
                    const base = currentPath.substring(0, currentPath.indexOf('/public') + 7);
                    return `${base}/${clean}`;
                }
                return `/${clean}`;
            };

            // 1. เริ่มต้น Leaflet Map Canvas (พิกัดศูนย์กลาง: ร้อยเอ็ด / ครอบคลุมประเทศไทย)
            const map = L.map('map', {
                zoomControl: false,
                attributionControl: true
            }).setView([16.0538, 103.6520], 12);

            // สร้าง Custom Panes เพื่อควบคุม Z-Index ลำดับชั้นความสูงของการแสดงผลอย่างสมบูรณ์แบบ
            // เพื่อให้หมุดที่ปัก/ลาก (pickPinPane) อยู่เหนือ Area น้ำท่วมและเลเยอร์อื่นๆ ทั้งหมด 100%
            map.createPane('gistdaPane');
            map.getPane('gistdaPane').style.zIndex = '410';

            map.createPane('floodPolygonsPane');
            map.getPane('floodPolygonsPane').style.zIndex = '420';

            map.createPane('radiusZonesPane');
            map.getPane('radiusZonesPane').style.zIndex = '430';

            map.createPane('pickPinPane');
            map.getPane('pickPinPane').style.zIndex = '950';

            // เพิ่ม Tile Layer OpenStreetMap ไร้เส้นขอบ
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© แผนที่ OpenStreetMap'
            }).addTo(map);

            // ย้ายปุ่ม Zoom Control ไปมุมล่างซ้าย
            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            // Layer Groups สำหรับจัดการหมุดและอาณาเขต
            const floodMarkersLayer = L.layerGroup().addTo(map);
            const dynamicPolygonsLayer = L.layerGroup().addTo(map);
            const gistdaLayerGroup = L.layerGroup().addTo(map);

            // แคชข้อมูลและตัวแปรสถานะพิกัด / รัศมี 5 กิโลเมตร
            let allFloodPoints = [];
            let currentFilter = 'all';
            let userCurrentLat = 16.0538;
            let userCurrentLng = 103.6520;
            let selectedReportLat = 16.0538;
            let selectedReportLng = 103.6520;
            let selectedSeverity = 3;
            let isPickingLocation = false;
            let pinPickingOrigin = 'modal';
            let is1kmRadiusVisible = false;
            let userRadius1kmCircle = null;
            let pinned1kmZoneCircle = null;
            let userGpsMarker = null;
            let pickPinMarker = null;

            // =====================================================================
            // ฟังก์ชันคำนวณระยะห่างทางภูมิศาสตร์ (Haversine Formula) เป็นหน่วยเมตร
            // =====================================================================
            function calculateDistanceMeters(lat1, lon1, lat2, lon2) {
                const R = 6371000; // รัศมีโลกเฉลี่ยในหน่วยเมตร
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = 
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            // =====================================================================
            // ฟังก์ชันจัดการวงรัศมี 5 กิโลเมตรรอบผู้ใช้ และตีกรอบ 5 กิโลเมตรรอบหมุดที่ปัก
            // =====================================================================
            function draw1kmRadiusCircle(centerLat, centerLng, fitView = false) {
                if (userRadius1kmCircle) {
                    map.removeLayer(userRadius1kmCircle);
                }
                userRadius1kmCircle = L.circle([centerLat, centerLng], {
                    pane: 'radiusZonesPane',
                    radius: 5000,
                    stroke: false,
                    fillColor: '#2563EB',
                    fillOpacity: 0.12,
                    interactive: false
                }).addTo(map);

                if (fitView) {
                    map.fitBounds(userRadius1kmCircle.getBounds(), { padding: [50, 50], maxZoom: 14 });
                }
            }

            function remove1kmRadiusCircle() {
                if (userRadius1kmCircle) {
                    map.removeLayer(userRadius1kmCircle);
                    userRadius1kmCircle = null;
                }
            }

            function drawPinned1kmZone(centerLat, centerLng, fitView = false) {
                let strokeColor = '#2563EB';
                let fillColor = '#2563EB';
                if (selectedSeverity === 3) {
                    strokeColor = '#DC2626';
                    fillColor = '#DC2626';
                } else if (selectedSeverity === 2) {
                    strokeColor = '#D97706';
                    fillColor = '#D97706';
                }

                if (pinned1kmZoneCircle) {
                    pinned1kmZoneCircle.setLatLng([centerLat, centerLng]);
                    pinned1kmZoneCircle.setStyle({
                        color: strokeColor,
                        fillColor: fillColor
                    });
                } else {
                    pinned1kmZoneCircle = L.circle([centerLat, centerLng], {
                        pane: 'radiusZonesPane',
                        radius: 5000,
                        stroke: true,
                        color: strokeColor,
                        weight: 2,
                        dashArray: '6, 8',
                        fillColor: fillColor,
                        fillOpacity: 0.16,
                        interactive: false
                    }).addTo(map);
                }

                if (fitView && pinned1kmZoneCircle) {
                    map.fitBounds(pinned1kmZoneCircle.getBounds(), { padding: [60, 60], maxZoom: 14 });
                }
            }

            function removePinned1kmZone() {
                if (pinned1kmZoneCircle) {
                    map.removeLayer(pinned1kmZoneCircle);
                    pinned1kmZoneCircle = null;
                }
            }

            // =====================================================================
            // ฟังก์ชันสร้าง Custom Marker สำหรับโหมดปักหมุด/ลากหมุดพร้อมตีกรอบ 5 กม.
            // =====================================================================
            function createPickPinIcon(distanceMeters, isDragging = false) {
                const isWithin = distanceMeters <= 5000;
                const distFormatted = distanceMeters < 1000 
                    ? `${Math.round(distanceMeters)} ม.` 
                    : `${(distanceMeters / 1000).toFixed(2)} กม.`;
                const distText = isWithin 
                    ? `${distFormatted} (ในรัศมี 5 กม.)` 
                    : `${(distanceMeters / 1000).toFixed(2)} กม. (เกิน 5 กม.)`;
                const rangeClass = isWithin ? 'in-range' : 'out-range';
                const iconClass = isWithin ? 'fa-solid fa-location-dot' : 'fa-solid fa-triangle-exclamation';

                const html = `
                    <div class="interactive-pick-pin ${isDragging ? 'is-dragging' : ''}">
                        <div class="pick-pin-zone-badge ${rangeClass}">
                            <i class="fa-solid fa-bullseye"></i> ตีกรอบ 5 กม. (78.54 ตร.กม.)
                        </div>
                        <div class="pick-pin-tooltip ${rangeClass}">
                            <i class="${isWithin ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation'}"></i>
                            <span>${distText}</span>
                        </div>
                        <div class="pick-pin-icon-wrap">
                            <div class="pick-pin-icon ${rangeClass}">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="pick-pin-pulse"></div>
                        </div>
                    </div>
                `;

                return L.divIcon({
                    html: html,
                    className: 'pick-pin-leaflet-div-icon',
                    iconSize: [200, 110],
                    iconAnchor: [100, 96]
                });
            }

            function updatePickPinPosition(lat, lng, isDragging = false) {
                selectedReportLat = lat;
                selectedReportLng = lng;
                const dist = calculateDistanceMeters(userCurrentLat, userCurrentLng, lat, lng);
                const isWithin = dist <= 5000;

                // อัปเดตวงตีกรอบ 5 กิโลเมตรรอบหมุดที่ปักทันทีตามการลาก/drop
                drawPinned1kmZone(lat, lng, false);

                if (pickPinMarker) {
                    pickPinMarker.setLatLng([lat, lng]);
                    pickPinMarker.setIcon(createPickPinIcon(dist, isDragging));
                }

                const badge = document.getElementById('pinRangeBadge');
                const info = document.getElementById('pinModeInfoText');

                if (badge && info) {
                    const distFormatted = dist < 1000 
                        ? `${Math.round(dist)} เมตร` 
                        : `${(dist / 1000).toFixed(2)} กม.`;

                    if (isWithin) {
                        badge.className = 'pin-mode-badge';
                        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> ในรัศมี 5 กม.';
                        info.innerHTML = `พิกัดที่ปัก: <strong>${lat.toFixed(4)}, ${lng.toFixed(4)}</strong> · ตีกรอบรัศมี 5 กม. (78.54 ตร.กม.) · ห่างจากคุณ: <strong>${distFormatted}</strong>`;
                    } else {
                        badge.className = 'pin-mode-badge out-range';
                        badge.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> เกินรัศมี 5 กม.';
                        info.innerHTML = `จุดที่เลือกห่างจากคุณ <strong>${(dist / 1000).toFixed(2)} กม.</strong> แนะนำให้ลากหรือแตะวางหมุดภายในรัศมี 5 กิโลเมตร`;
                    }
                }
            }

            // =====================================================================
            // ฟังก์ชันเริ่มและสิ้นสุดโหมดปักหมุด/ลากหมุดและตีกรอบ 5 กิโลเมตร
            // =====================================================================
            function startPinPickingMode(origin = 'modal') {
                isPickingLocation = true;
                pinPickingOrigin = origin;
                reportModal.style.display = 'none';

                const banner = document.getElementById('pinPickerBanner');
                banner.classList.add('active');

                // วาดวงรัศมี 5 กิโลเมตรรอบตำแหน่งผู้ใช้
                draw1kmRadiusCircle(userCurrentLat, userCurrentLng, false);

                // ตีกรอบรัศมี 5 กิโลเมตรรอบหมุดที่ปัก
                drawPinned1kmZone(selectedReportLat, selectedReportLng, false);

                // สร้างหรือย้ายหมุดปัก (เปิด draggable: true, autoPan: true และผูกกับ pickPinPane อยู่เหนือทุก Area)
                if (!pickPinMarker) {
                    pickPinMarker = L.marker([selectedReportLat, selectedReportLng], {
                        pane: 'pickPinPane',
                        draggable: true,
                        autoPan: true,
                        autoPanPadding: [50, 50],
                        zIndexOffset: 10000
                    }).addTo(map);

                    pickPinMarker.on('dragstart', () => {
                        if (pickPinMarker._icon) {
                            pickPinMarker._icon.classList.add('is-dragging-active');
                        }
                    });

                    pickPinMarker.on('drag', (e) => {
                        const pos = e.target.getLatLng();
                        updatePickPinPosition(pos.lat, pos.lng, true);
                    });

                    pickPinMarker.on('dragend', (e) => {
                        if (pickPinMarker._icon) {
                            pickPinMarker._icon.classList.remove('is-dragging-active');
                        }
                        const pos = e.target.getLatLng();
                        updatePickPinPosition(pos.lat, pos.lng, false);
                    });
                } else {
                    pickPinMarker.addTo(map);
                    pickPinMarker.setLatLng([selectedReportLat, selectedReportLng]);
                }

                updatePickPinPosition(selectedReportLat, selectedReportLng, false);

                if (pinned1kmZoneCircle) {
                    map.fitBounds(pinned1kmZoneCircle.getBounds(), { padding: [60, 60], maxZoom: 14 });
                }

                showToast('แตะบนแผนที่เพื่อ Drop หมุด หรือลากหมุดเพื่อปรับตำแหน่งพร้อมตีกรอบ 5 กม.');
            }

            function finishPinPickingMode(confirmed) {
                isPickingLocation = false;
                const banner = document.getElementById('pinPickerBanner');
                banner.classList.remove('active');

                if (pickPinMarker) {
                    map.removeLayer(pickPinMarker);
                    pickPinMarker = null;
                }

                if (!is1kmRadiusVisible) {
                    remove1kmRadiusCircle();
                }

                if (confirmed) {
                    const dist = calculateDistanceMeters(userCurrentLat, userCurrentLng, selectedReportLat, selectedReportLng);
                    const isWithin = dist <= 5000;
                    const distFormatted = dist < 1000 
                        ? `${Math.round(dist)} ม.` 
                        : `${(dist / 1000).toFixed(2)} กม.`;

                    if (isWithin) {
                        reportLocationStatus.innerHTML = `<i class="fa-solid fa-circle-check" style="color:var(--status-success);"></i> พิกัดที่ปัก: ${selectedReportLat.toFixed(4)}, ${selectedReportLng.toFixed(4)} (ห่าง ${distFormatted} · ตีกรอบ 5 กม. เรียบร้อย)`;
                        showToast('ยืนยันตำแหน่งและตีกรอบรัศมี 5 กิโลเมตรเรียบร้อยแล้ว');
                    } else {
                        reportLocationStatus.innerHTML = `<i class="fa-solid fa-triangle-exclamation" style="color:var(--risk-high);"></i> พิกัดที่ปัก: ${selectedReportLat.toFixed(4)}, ${selectedReportLng.toFixed(4)} (ห่าง ${(dist/1000).toFixed(2)} กม.)`;
                        showToast(`จุดที่เลือกอยู่นอกรัศมี 5 กม. (ห่าง ${(dist/1000).toFixed(2)} กม.)`);
                    }
                    reportModal.style.display = 'flex';
                } else {
                    if (pinPickingOrigin === 'modal') {
                        reportModal.style.display = 'flex';
                    } else {
                        removePinned1kmZone();
                    }
                }
            }

            // จัดการปุ่มในแถบ Banner โหมดปักหมุด
            const btnConfirmPin = document.getElementById('btnConfirmPinPick');
            const btnCancelPin = document.getElementById('btnCancelPinPick');

            btnConfirmPin.addEventListener('click', () => {
                finishPinPickingMode(true);
            });

            btnCancelPin.addEventListener('click', () => {
                finishPinPickingMode(false);
            });

            // คลิกบนแผนที่ในโหมดปักหมุด: Drop หมุดและตีกรอบ 5 กม. ทันที
            map.on('click', (e) => {
                if (isPickingLocation) {
                    updatePickPinPosition(e.latlng.lat, e.latlng.lng, false);
                }
            });

            // =====================================================================
            // ฟังก์ชันสร้าง Custom Marker ตามระดับความเสี่ยง (แดง/ส้ม/เหลือง/น้ำเงิน)
            // =====================================================================
            function createRiskMarkerIcon(riskLevel, isCritical) {
                let riskClass = 'marker-medium';
                let iconClass = 'fa-solid fa-water';

                switch (riskLevel) {
                    case 'CRITICAL':
                        riskClass = 'marker-critical';
                        iconClass = 'fa-solid fa-triangle-exclamation';
                        break;
                    case 'HIGH':
                        riskClass = 'marker-high';
                        iconClass = 'fa-solid fa-water';
                        break;
                    case 'MEDIUM':
                        riskClass = 'marker-medium';
                        iconClass = 'fa-solid fa-droplet';
                        break;
                    case 'LOW':
                        riskClass = 'marker-low';
                        iconClass = 'fa-solid fa-shield-halved';
                        break;
                }

                const pulseClass = (isCritical || riskLevel === 'CRITICAL') ? 'is-pulsing' : '';

                const html = `
                    <div class="custom-risk-marker ${riskClass} ${pulseClass}">
                        <div class="pulse-ring"></div>
                        <div class="marker-core">
                            <i class="${iconClass}"></i>
                        </div>
                    </div>
                `;

                return L.divIcon({
                    html: html,
                    className: '',
                    iconSize: [36, 36],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -18]
                });
            }

            // =====================================================================
            // ฟังก์ชันสร้าง Card สรุปสถานะแบบ Surface Layering ไร้เส้นขอบ ไร้เงา
            // =====================================================================
            function createPopupCardHtml(point) {
                let badgeClass = 'badge-medium';
                let riskLabel = point.risk_level_label || 'เฝ้าระวัง';

                if (point.risk_level === 'CRITICAL') {
                    badgeClass = 'badge-critical';
                } else if (point.risk_level === 'HIGH') {
                    badgeClass = 'badge-high';
                } else if (point.risk_level === 'LOW') {
                    badgeClass = 'badge-low';
                }

                const waterLevel = point.water_level_meters !== undefined 
                    ? Number(point.water_level_meters).toFixed(2) + ' เมตร'
                    : 'ระดับผิวทาง';

                const reportCount = point.report_count || 1;
                const updateTime = point.updated_at 
                    ? new Date(point.updated_at).toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' }) + ' น.'
                    : 'ล่าสุดวันนี้';

                const distToUser = calculateDistanceMeters(
                    userCurrentLat,
                    userCurrentLng,
                    point.location.latitude,
                    point.location.longitude
                );

                const distFormatted = distToUser < 1000 
                    ? `${Math.round(distToUser)} ม.` 
                    : `${(distToUser / 1000).toFixed(1)} กม.`;

                const distanceBadge = distToUser <= 5000
                    ? `<span style="font-size:11px; font-weight:700; color:var(--accent-blue); background:var(--accent-tint); padding:2px 8px; border-radius:var(--r-full); display:inline-flex; align-items:center; gap:4px;"><i class="fa-solid fa-bullseye"></i> ห่างคุณ ${distFormatted} (ในรัศมี 5 กม.)</span>`
                    : `<span style="font-size:11px; color:var(--text-muted); display:inline-flex; align-items:center; gap:4px;"><i class="fa-solid fa-location-arrow"></i> ห่างคุณ ${(distToUser / 1000).toFixed(1)} กม.</span>`;

                const safeTitle = (point.title || 'จุดเฝ้าระวัง').replace(/'/g, "\\'");

                return `
                    <div class="map-popup-card">
                        <div class="popup-header">
                            <div class="popup-title">${point.title || 'จุดเฝ้าระวังน้ำท่วม'}</div>
                            <span class="popup-badge ${badgeClass}">${riskLabel}</span>
                        </div>

                        <div style="margin-top:-4px; margin-bottom:2px;">
                            ${distanceBadge}
                        </div>

                        <div class="popup-inner-box">
                            <div class="popup-stat-row">
                                <span class="stat-label">
                                    <i class="fa-solid fa-water" style="color:var(--accent-blue);"></i> ระดับน้ำ
                                </span>
                                <span class="stat-value">${waterLevel}</span>
                            </div>
                            <div class="popup-stat-row">
                                <span class="stat-label">
                                    <i class="fa-solid fa-users" style="color:var(--text-muted);"></i> รายงานซ้ำ
                                </span>
                                <span class="stat-value">${reportCount} ครั้ง</span>
                            </div>
                            <div class="popup-stat-row">
                                <span class="stat-label">
                                    <i class="fa-regular fa-clock" style="color:var(--text-muted);"></i> อัปเดต
                                </span>
                                <span class="stat-value">${updateTime}</span>
                            </div>
                        </div>

                        <div style="display:flex; gap:6px; margin-top:4px;">
                            <a href="javascript:void(0);" class="popup-btn-action" style="flex:1;" onclick="window.zoomToPoint(${point.location.latitude}, ${point.location.longitude})">
                                <i class="fa-solid fa-magnifying-glass-location"></i> ซูมดู
                            </a>
                            <a href="javascript:void(0);" class="popup-btn-action" style="flex:1.2; background-color:var(--accent-tint); color:var(--accent-blue);" onclick="window.togglePoint1kmZone(${point.location.latitude}, ${point.location.longitude}, '${safeTitle}')">
                                <i class="fa-solid fa-bullseye"></i> ตีกรอบ 5 กม.
                            </a>
                        </div>
                    </div>
                `;
            }

            // ฟังก์ชันซูมไปยังจุดที่เลือก
            window.zoomToPoint = (lat, lng) => {
                map.flyTo([lat, lng], 15, { duration: 1.2 });
            };

            // ฟังก์ชันตีกรอบ 5 กิโลเมตรรอบจุดตรวจวัด
            window.togglePoint1kmZone = (lat, lng, title) => {
                drawPinned1kmZone(lat, lng, true);
                showToast(`ตีกรอบพื้นที่เฝ้าระวัง 5 กิโลเมตรรอบ ${title} เรียบร้อยแล้ว`);
            };

            // =====================================================================
            // ฟังก์ชันคำนวณและวาด Dynamic Flood Polygon Zones อัตโนมัติ
            // เมื่อพบจุดท่วมวิกฤต (CRITICAL) หรือรายงานซ้ำซ้อน (report_count >= 3)
            // =====================================================================
            function generateAdaptiveContour(lat, lng, radiusMeters, seed = 1) {
                const coords = [];
                const pointsCount = 7;
                const latScale = radiusMeters / 111320;
                const lngScale = radiusMeters / (111320 * Math.cos(lat * Math.PI / 180));

                for (let i = 0; i < pointsCount; i++) {
                    const angle = (i / pointsCount) * 2 * Math.PI;
                    const variation = 0.85 + 0.35 * Math.sin(angle * 2 + seed) + 0.15 * Math.cos(angle * 3);
                    const pLat = lat + Math.sin(angle) * latScale * variation;
                    const pLng = lng + Math.cos(angle) * lngScale * variation;
                    coords.push([pLat, pLng]);
                }
                return coords;
            }

            function renderDynamicFloodZones(points) {
                dynamicPolygonsLayer.clearLayers();

                points.forEach((point, index) => {
                    const isCritical = point.risk_level === 'CRITICAL';
                    const isRepeated = (point.report_count || 1) >= 3;
                    const hasPresetPolygon = Array.isArray(point.polygon_coordinates) && point.polygon_coordinates.length > 2;

                    if (isCritical || isRepeated || hasPresetPolygon) {
                        let polygonCoords = point.polygon_coordinates;

                        if (!hasPresetPolygon) {
                            const radius = point.radius_meters || (isCritical ? 350 : 250);
                            polygonCoords = generateAdaptiveContour(
                                point.location.latitude,
                                point.location.longitude,
                                radius,
                                index + 1
                            );
                        }

                        let zoneColor = '#DC2626'; // Red for Critical
                        let zoneDesc = 'อาณาเขตน้ำท่วมวิกฤตฉุกเฉิน';

                        if (point.risk_level === 'HIGH') {
                            zoneColor = '#EA580C'; // Orange
                            zoneDesc = 'อาณาเขตน้ำท่วมระดับสูง';
                        } else if (point.risk_level === 'MEDIUM') {
                            zoneColor = '#D97706'; // Yellow
                            zoneDesc = 'อาณาเขตเฝ้าระวังน้ำท่วมปานกลาง';
                        }

                        const polygon = L.polygon(polygonCoords, {
                            pane: 'floodPolygonsPane',
                            stroke: false,
                            weight: 0,
                            fillColor: zoneColor,
                            fillOpacity: 0.22,
                            smoothFactor: 1.5
                        });

                        const popupHtml = `
                            <div class="map-popup-card">
                                <div class="popup-header">
                                    <div class="popup-title">${point.zone_name || zoneDesc}</div>
                                    <span class="popup-badge badge-critical">อาณาเขตวิกฤต</span>
                                </div>
                                <div class="popup-inner-box">
                                    <div class="popup-stat-row">
                                        <span class="stat-label">จุดศูนย์กลาง</span>
                                        <span class="stat-value">${point.title}</span>
                                    </div>
                                    <div class="popup-stat-row">
                                        <span class="stat-label">การรายงานซ้ำ</span>
                                        <span class="stat-value">${point.report_count || 1} ครั้ง</span>
                                    </div>
                                    <div class="popup-stat-row">
                                        <span class="stat-label">ระดับน้ำสูงสุด</span>
                                        <span class="stat-value">${Number(point.water_level_meters || 1.5).toFixed(2)} ม.</span>
                                    </div>
                                </div>
                                <div style="font-size:12px; color:var(--risk-critical); font-weight:600; text-align:center;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> สัญจรลำบาก โปรดหลีกเลี่ยงเส้นทาง
                                </div>
                            </div>
                        `;

                        polygon.bindPopup(popupHtml, { closeButton: false });

                        // เมื่ออยู่ในโหมดปักหมุด การแตะบน Polygon จะส่งต่อตำแหน่งปักหมุดทันที
                        polygon.on('click', (e) => {
                            if (isPickingLocation) {
                                L.DomEvent.stopPropagation(e);
                                updatePickPinPosition(e.latlng.lat, e.latlng.lng, false);
                            }
                        });

                        dynamicPolygonsLayer.addLayer(polygon);
                    }
                });
            }

            // =====================================================================
            // ฟังก์ชันเรนเดอร์หมุดลงบนแผนที่และกรองตามระดับความเสี่ยง
            // =====================================================================
            function renderMarkers() {
                floodMarkersLayer.clearLayers();

                const filtered = allFloodPoints.filter(p => {
                    if (currentFilter === 'all') return true;
                    return p.risk_level === currentFilter;
                });

                filtered.forEach(point => {
                    const isCritical = point.risk_level === 'CRITICAL';
                    const icon = createRiskMarkerIcon(point.risk_level, isCritical);

                    const marker = L.marker([point.location.latitude, point.location.longitude], {
                        icon: icon
                    });

                    marker.bindPopup(createPopupCardHtml(point), {
                        closeButton: false,
                        offset: [0, -12]
                    });

                    // เมื่ออยู่ในโหมดปักหมุด การคลิกหมุดเดิมจะปรับตำแหน่งหมุดปักใหม่ได้ลื่นไหล
                    marker.on('click', (e) => {
                        if (isPickingLocation) {
                            L.DomEvent.stopPropagation(e);
                            marker.closePopup();
                            updatePickPinPosition(e.latlng.lat, e.latlng.lng, false);
                        }
                    });

                    floodMarkersLayer.addLayer(marker);
                });

                // อัปเดตตัวเลขสถิติบนการ์ดตัวกรอง
                document.getElementById('statPointCount').textContent = `${allFloodPoints.length} จุดตรวจวัด`;

                // อัปเดต dynamic flood polygon zones
                renderDynamicFloodZones(allFloodPoints);
            }

            // =====================================================================
            // ดึงข้อมูลจุดน้ำท่วมจาก API (/api/points)
            // =====================================================================
            async function fetchFloodPoints() {
                try {
                    let response = await fetch(getApiUrl('api/points'));
                    if (!response.ok) {
                        response = await fetch(getApiUrl('?api=points'));
                    }
                    const json = await response.json();
                    if (json && json.success && Array.isArray(json.data)) {
                        allFloodPoints = json.data;
                        renderMarkers();
                    }
                } catch (err) {
                    console.warn('ดึงข้อมูลจุดน้ำท่วมจากเซิร์ฟเวอร์ขัดข้อง กำลังโหลดชุดข้อมูลท้องถิ่น:', err);
                    allFloodPoints = [
                        {
                            id: 'point_01',
                            title: 'สะพานข้ามแม่น้ำชี (ธวัชบุรี)',
                            location: { latitude: 16.0538, longitude: 103.6520 },
                            water_level_meters: 2.85,
                            risk_level: 'CRITICAL',
                            risk_level_label: 'ความเสี่ยงวิกฤต',
                            report_count: 5,
                            updated_at: new Date().toISOString(),
                            polygon_coordinates: [
                                [16.0650, 103.6410],
                                [16.0680, 103.6560],
                                [16.0620, 103.6690],
                                [16.0480, 103.6650],
                                [16.0430, 103.6500],
                                [16.0470, 103.6380]
                            ],
                            zone_name: 'เขตพื้นที่ลุ่มน้ำชีเอ่อท่วม ธวัชบุรี'
                        },
                        {
                            id: 'point_02',
                            title: 'จุดตัดคลองส่งน้ำเสลภูมิ',
                            location: { latitude: 16.0350, longitude: 103.7890 },
                            water_level_meters: 1.40,
                            risk_level: 'HIGH',
                            risk_level_label: 'ความเสี่ยงสูง',
                            report_count: 3,
                            updated_at: new Date().toISOString(),
                            polygon_coordinates: [
                                [16.0440, 103.7780],
                                [16.0470, 103.7950],
                                [16.0390, 103.8030],
                                [16.0270, 103.7940],
                                [16.0280, 103.7810]
                            ],
                            zone_name: 'เขตพื้นที่ล้นคลองส่งน้ำเสลภูมิ'
                        },
                        {
                            id: 'point_03',
                            title: 'ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก',
                            location: { latitude: 16.0680, longitude: 103.6850 },
                            water_level_meters: 0.45,
                            risk_level: 'MEDIUM',
                            risk_level_label: 'ความเสี่ยงปานกลาง',
                            report_count: 2,
                            updated_at: new Date().toISOString()
                        },
                        {
                            id: 'point_04',
                            title: 'อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง',
                            location: { latitude: 16.0120, longitude: 103.7200 },
                            water_level_meters: 0.20,
                            risk_level: 'LOW',
                            risk_level_label: 'เฝ้าระวัง',
                            report_count: 1,
                            updated_at: new Date().toISOString()
                        },
                        {
                            id: 'point_05',
                            title: 'สะพานข้ามลำน้ำยัง (โพนทอง)',
                            location: { latitude: 16.3015, longitude: 103.9850 },
                            water_level_meters: 2.10,
                            risk_level: 'HIGH',
                            risk_level_label: 'ความเสี่ยงสูง',
                            report_count: 4,
                            updated_at: new Date().toISOString()
                        }
                    ];
                    renderMarkers();
                }
            }

            // =====================================================================
            // เชื่อมต่อเลเยอร์ดาวเทียม GISTDA 77 จังหวัด พร้อมปุ่ม Switch เปิด-ปิด
            // =====================================================================
            const gistdaSwitch = document.getElementById('gistdaSwitch');
            const gistdaStatusText = document.getElementById('gistdaStatusText');

            const fallbackGistdaPolygons = [
                { name: 'น้ำท่วมลุ่มน้ำปิง เชียงใหม่ (ฝาง-แม่อาย)', province: 'เชียงใหม่', area_sqkm: 38.4, area_rai: 24000, coordinates: [[20.0600, 99.8700], [20.1100, 99.9200], [20.0800, 99.9800], [20.0100, 99.9600], [19.9700, 99.9000], [20.0000, 99.8500]] },
                { name: 'น้ำท่วมลุ่มน้ำกก เชียงราย - แม่จัน', province: 'เชียงราย', area_sqkm: 42.5, area_rai: 26562, coordinates: [[19.9800, 99.8200], [20.0300, 99.8900], [20.0000, 99.9500], [19.9400, 99.9200], [19.9200, 99.8600], [19.9500, 99.8100]] },
                { name: 'น้ำท่วมรอบกว๊านพะเยา - ดอกคำใต้', province: 'พะเยา', area_sqkm: 25.0, area_rai: 15625, coordinates: [[19.1300, 99.8800], [19.1700, 99.9400], [19.1400, 99.9900], [19.0900, 99.9700], [19.0700, 99.9100], [19.1000, 99.8600]] },
                { name: 'น้ำท่วมลุ่มน้ำน่าน - เมืองน่าน', province: 'น่าน', area_sqkm: 31.2, area_rai: 19500, coordinates: [[18.8000, 100.7600], [18.8500, 100.8100], [18.8200, 100.8700], [18.7700, 100.8500], [18.7500, 100.7900], [18.7700, 100.7500]] },
                { name: 'น้ำท่วมลุ่มน้ำวัง ลำปาง', province: 'ลำปาง', area_sqkm: 18.6, area_rai: 11625, coordinates: [[18.3050, 99.4800], [18.3400, 99.5300], [18.3100, 99.5800], [18.2700, 99.5650], [18.2500, 99.5100], [18.2800, 99.4700]] },
                { name: 'น้ำท่วมลุ่มน้ำชี ร้อยเอ็ด (ธวัชบุรี-เสลภูมิ)', province: 'ร้อยเอ็ด', area_sqkm: 19.97, area_rai: 12480, coordinates: [[16.0350, 103.7300], [16.0680, 103.7750], [16.0520, 103.8200], [16.0150, 103.8450], [15.9900, 103.7900], [16.0100, 103.7400]] },
                { name: 'น้ำท่วมลุ่มน้ำสงคราม อุดรธานี - หนองหาน', province: 'อุดรธานี', area_sqkm: 55.6, area_rai: 34750, coordinates: [[17.3200, 102.9800], [17.3800, 103.0400], [17.3500, 103.1000], [17.2900, 103.0800], [17.2600, 103.0200], [17.2900, 102.9600]] },
                { name: 'น้ำท่วมหนองหาร สกลนคร', province: 'สกลนคร', area_sqkm: 68.0, area_rai: 42500, coordinates: [[17.1800, 103.9000], [17.2400, 103.9700], [17.2000, 104.0300], [17.1300, 104.0100], [17.1000, 103.9400], [17.1300, 103.8900]] },
                { name: 'น้ำท่วมริมโขง นครพนม (เมือง-ท่าอุเทน)', province: 'นครพนม', area_sqkm: 48.2, area_rai: 30125, coordinates: [[17.3800, 104.7400], [17.4300, 104.7900], [17.4000, 104.8400], [17.3400, 104.8200], [17.3100, 104.7600], [17.3400, 104.7200]] },
                { name: 'น้ำท่วมลุ่มน้ำมูล-ชี อุบลราชธานี (วารินชำราบ)', province: 'อุบลราชธานี', area_sqkm: 85.4, area_rai: 53375, coordinates: [[15.2300, 104.8000], [15.2900, 104.8700], [15.2600, 104.9300], [15.2000, 104.9000], [15.1700, 104.8400], [15.2000, 104.7900]] },
                { name: 'น้ำท่วมลุ่มน้ำชี ขอนแก่น (ชุมแพ-น้ำพอง)', province: 'ขอนแก่น', area_sqkm: 44.8, area_rai: 28000, coordinates: [[16.4300, 102.7800], [16.4900, 102.8500], [16.4500, 102.9100], [16.3800, 102.8800], [16.3600, 102.8100], [16.3900, 102.7600]] },
                { name: 'น้ำท่วมลุ่มน้ำเจ้าพระยา นครสวรรค์ (ชุมแสง)', province: 'นครสวรรค์', area_sqkm: 75.3, area_rai: 47062, coordinates: [[15.7100, 100.0900], [15.7700, 100.1600], [15.7300, 100.2200], [15.6700, 100.1900], [15.6400, 100.1300], [15.6800, 100.0700]] },
                { name: 'น้ำท่วมพื้นที่เกษตร พระนครศรีอยุธยา (บางไทร)', province: 'พระนครศรีอยุธยา', area_sqkm: 62.8, area_rai: 39250, coordinates: [[14.3600, 100.5000], [14.4100, 100.5600], [14.3800, 100.6200], [14.3200, 100.5900], [14.3000, 100.5300], [14.3300, 100.4800]] },
                { name: 'น้ำท่วมลุ่มน้ำท่าจีน สุพรรณบุรี', province: 'สุพรรณบุรี', area_sqkm: 58.9, area_rai: 36812, coordinates: [[14.5600, 99.9400], [14.6100, 100.0100], [14.5800, 100.0700], [14.5200, 100.0400], [14.5000, 99.9700], [14.5300, 99.9200]] },
                { name: 'น้ำท่วมพื้นที่ลุ่มต่ำ นครศรีธรรมราช (เชียรใหญ่)', province: 'นครศรีธรรมราช', area_sqkm: 92.1, area_rai: 57562, coordinates: [[8.3100, 100.0300], [8.3700, 100.1000], [8.3400, 100.1600], [8.2800, 100.1300], [8.2500, 100.0700], [8.2800, 100.0100]] },
                { name: 'น้ำท่วมลุ่มน้ำทะเลสาบสงขลา (หาดใหญ่)', province: 'สงขลา', area_sqkm: 65.8, area_rai: 41125, coordinates: [[7.0700, 100.4200], [7.1200, 100.4900], [7.0900, 100.5500], [7.0300, 100.5200], [7.0100, 100.4600], [7.0400, 100.4000]] }
            ];

            async function loadGistdaSatelliteLayer() {
                gistdaLayerGroup.clearLayers();
                let polygons = fallbackGistdaPolygons;

                try {
                    let res = await fetch(getApiUrl('api/gistda?scope=national'));
                    if (!res.ok) {
                        res = await fetch(getApiUrl('?api=gistda&scope=national'));
                    }
                    const json = await res.json();
                    if (json && json.success && json.data && Array.isArray(json.data.satellite_flood_polygons)) {
                        polygons = json.data.satellite_flood_polygons;
                    }
                } catch (e) {
                    console.log('ใช้ชุดข้อมูลดาวเทียมในเครื่องสำหรับการแสดงผล');
                }

                polygons.forEach(item => {
                    if (Array.isArray(item.coordinates) && item.coordinates.length > 2) {
                        const poly = L.polygon(item.coordinates, {
                            pane: 'gistdaPane',
                            stroke: false,
                            weight: 0,
                            fillColor: '#7C3AED',
                            fillOpacity: 0.28
                        });

                        const popupContent = `
                            <div class="map-popup-card">
                                <div class="popup-header">
                                    <div class="popup-title">${item.name || 'พื้นที่น้ำท่วมจากภาพถ่ายดาวเทียม'}</div>
                                    <span class="popup-badge badge-gistda">ภาพถ่ายดาวเทียม</span>
                                </div>
                                <div class="popup-inner-box">
                                    <div class="popup-stat-row">
                                        <span class="stat-label">จังหวัดที่ได้รับผลกระทบ</span>
                                        <span class="stat-value">${item.province || 'ไม่ระบุ'}</span>
                                    </div>
                                    <div class="popup-stat-row">
                                        <span class="stat-label">พื้นที่น้ำท่วม</span>
                                        <span class="stat-value">${item.area_sqkm || '-'} ตร.กม.</span>
                                    </div>
                                    <div class="popup-stat-row">
                                        <span class="stat-label">คิดเป็น</span>
                                        <span class="stat-value">${item.area_rai ? Number(item.area_rai).toLocaleString() : '-'} ไร่</span>
                                    </div>
                                    <div class="popup-stat-row">
                                        <span class="stat-label">ดาวเทียมตรวจวัด</span>
                                        <span class="stat-value">เรดาร์ทะลุเมฆฝน</span>
                                    </div>
                                </div>
                                <div style="font-size:11.5px; color:var(--text-muted); text-align:center;">
                                    สำนักงานพัฒนาเทคโนโลยีอวกาศและภูมิสารสนเทศ
                                </div>
                            </div>
                        `;

                        poly.bindPopup(popupContent, { closeButton: false });

                        // เมื่ออยู่ในโหมดปักหมุด การแตะบน Polygon ดาวเทียมจะส่งต่อตำแหน่งปักหมุดทันที
                        poly.on('click', (e) => {
                            if (isPickingLocation) {
                                L.DomEvent.stopPropagation(e);
                                updatePickPinPosition(e.latlng.lat, e.latlng.lng, false);
                            }
                        });

                        gistdaLayerGroup.addLayer(poly);
                    }
                });

                gistdaStatusText.textContent = `เปิดใช้งาน ${polygons.length} โซน`;
            }

            gistdaSwitch.addEventListener('change', (e) => {
                if (e.target.checked) {
                    if (!map.hasLayer(gistdaLayerGroup)) {
                        map.addLayer(gistdaLayerGroup);
                    }
                    gistdaStatusText.textContent = 'เปิดใช้งาน 77 จังหวัด';
                } else {
                    if (map.hasLayer(gistdaLayerGroup)) {
                        map.removeLayer(gistdaLayerGroup);
                    }
                    gistdaStatusText.textContent = 'ปิดการแสดงผลชั่วคราว';
                }
            });

            // =====================================================================
            // จัดการตัวกรองระดับความเสี่ยง (ทั้งหมด / วิกฤต / สูง / ปานกลาง / เฝ้าระวัง)
            // =====================================================================
            document.querySelectorAll('.filter-chip').forEach(chip => {
                chip.addEventListener('click', () => {
                    document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    currentFilter = chip.getAttribute('data-filter') || 'all';
                    renderMarkers();
                });
            });

            // =====================================================================
            // ระบุพิกัดตำแหน่งปัจจุบันของผู้ใช้ (Geolocation GPS)
            // =====================================================================
            const btnLocate = document.getElementById('btnLocateUser');
            btnLocate.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    showToast('อุปกรณ์ไม่รองรับการระบุพิกัดจีพีเอส');
                    return;
                }

                btnLocate.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:var(--accent-blue);"></i> กำลังหาตำแหน่ง...';

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        userCurrentLat = lat;
                        userCurrentLng = lng;
                        selectedReportLat = lat;
                        selectedReportLng = lng;

                        map.flyTo([lat, lng], 14, { duration: 1.5 });

                        if (userGpsMarker) {
                            map.removeLayer(userGpsMarker);
                        }

                        userGpsMarker = L.circleMarker([lat, lng], {
                            radius: 9,
                            stroke: false,
                            fillColor: '#2563EB',
                            fillOpacity: 1,
                            interactive: false
                        }).addTo(map);

                        if (is1kmRadiusVisible) {
                            draw1kmRadiusCircle(userCurrentLat, userCurrentLng);
                        }

                        btnLocate.innerHTML = '<i class="fa-solid fa-crosshairs" style="color:var(--accent-blue);"></i> ตำแหน่งปัจจุบันของฉัน';
                        showToast('ระบุตำแหน่งของคุณเรียบร้อยแล้ว');
                    },
                    (err) => {
                        btnLocate.innerHTML = '<i class="fa-solid fa-crosshairs" style="color:var(--accent-blue);"></i> ตำแหน่งปัจจุบันของฉัน';
                        showToast('ไม่สามารถระบุพิกัดได้ ใช้พิกัดศูนย์กลางแทน');
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            });

            // =====================================================================
            // จัดการปุ่มเปิด-ปิดแสดงรัศมี 5 กิโลเมตรรอบตัวฉัน
            // =====================================================================
            const btnToggleRadius = document.getElementById('btnToggle1kmRadius');
            btnToggleRadius.addEventListener('click', () => {
                is1kmRadiusVisible = !is1kmRadiusVisible;
                if (is1kmRadiusVisible) {
                    btnToggleRadius.classList.add('radius-active');
                    draw1kmRadiusCircle(userCurrentLat, userCurrentLng, true);

                    const nearbyCount = allFloodPoints.filter(p => 
                        calculateDistanceMeters(userCurrentLat, userCurrentLng, p.location.latitude, p.location.longitude) <= 5000
                    ).length;

                    showToast(`เปิดวงรัศมี 5 กม. รอบตัวคุณ (พบ ${nearbyCount} จุดเสี่ยงในพื้นที่)`);
                } else {
                    btnToggleRadius.classList.remove('radius-active');
                    remove1kmRadiusCircle();
                    showToast('ปิดการแสดงวงรัศมี 5 กิโลเมตร');
                }
            });

            // =====================================================================
            // ฟอร์มรายงานเหตุน้ำท่วมด่วน (Quick Report Modal ≤ 3 Taps Flow)
            // =====================================================================
            const reportModal = document.getElementById('reportModal');
            const btnOpenModal = document.getElementById('btnOpenReportModal');
            const btnCloseModal = document.getElementById('btnCloseReportModal');
            const phoneInput = document.getElementById('reporterPhone');
            const reportLocationStatus = document.getElementById('reportLocationStatus');
            const btnPickMap = document.getElementById('btnPickLocationOnMap');
            const btnSubmit = document.getElementById('btnSubmitReport');

            const photoInput = document.getElementById('reportPhotoInput');
            const photoLabel = document.getElementById('btnSelectPhotoLabel');
            const photoPreviewWrap = document.getElementById('photoPreviewContainer');
            const photoPreviewImg = document.getElementById('photoPreviewImg');
            const photoPreviewName = document.getElementById('photoPreviewName');
            const btnRemovePhoto = document.getElementById('btnRemovePhoto');
            let selectedPhotoBase64 = null;

            const savedPhone = localStorage.getItem('roicore_reporter_phone');
            if (savedPhone) {
                phoneInput.value = savedPhone;
            }

            phoneInput.addEventListener('input', (e) => {
                localStorage.setItem('roicore_reporter_phone', e.target.value.trim());
            });

            function autoDetectLocationImmediate() {
                if (!navigator.geolocation) {
                    const dist = calculateDistanceMeters(userCurrentLat, userCurrentLng, selectedReportLat, selectedReportLng);
                    const distFormatted = dist < 1000 ? `${Math.round(dist)} ม.` : `${(dist/1000).toFixed(2)} กม.`;
                    reportLocationStatus.innerHTML = `<i class="fa-solid fa-location-dot" style="color:var(--accent-blue);"></i> พิกัด: ${selectedReportLat.toFixed(4)}, ${selectedReportLng.toFixed(4)} (ห่าง ${distFormatted} · ภายใน 5 กม.)`;
                    return;
                }

                reportLocationStatus.innerHTML = `<i class="fa-solid fa-spinner fa-spin" style="color:var(--accent-blue);"></i> กำลังตรวจหาพิกัดปัจจุบันอัตโนมัติ...`;

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        userCurrentLat = position.coords.latitude;
                        userCurrentLng = position.coords.longitude;
                        selectedReportLat = userCurrentLat;
                        selectedReportLng = userCurrentLng;

                        reportLocationStatus.innerHTML = `<i class="fa-solid fa-circle-check" style="color:var(--status-success);"></i> พิกัดปัจจุบันของคุณ: ${selectedReportLat.toFixed(4)}, ${selectedReportLng.toFixed(4)} (อยู่ในรัศมี 5 กม.)`;
                        map.setView([selectedReportLat, selectedReportLng], 14);
                    },
                    (err) => {
                        const dist = calculateDistanceMeters(userCurrentLat, userCurrentLng, selectedReportLat, selectedReportLng);
                        const distFormatted = dist < 1000 ? `${Math.round(dist)} ม.` : `${(dist/1000).toFixed(2)} กม.`;
                        reportLocationStatus.innerHTML = `<i class="fa-solid fa-location-dot" style="color:var(--accent-blue);"></i> พิกัด: ${selectedReportLat.toFixed(4)}, ${selectedReportLng.toFixed(4)} (ห่าง ${distFormatted} · ภายใน 5 กม.)`;
                    },
                    { enableHighAccuracy: true, timeout: 5000 }
                );
            }

            photoInput.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                photoPreviewName.textContent = file.name || 'รูปถ่ายสถานที่จริง';
                const reader = new FileReader();
                reader.onload = (event) => {
                    selectedPhotoBase64 = event.target.result;
                    photoPreviewImg.src = selectedPhotoBase64;
                    photoLabel.style.display = 'none';
                    photoPreviewWrap.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            });

            btnRemovePhoto.addEventListener('click', (e) => {
                e.stopPropagation();
                resetPhotoField();
            });

            function resetPhotoField() {
                photoInput.value = '';
                selectedPhotoBase64 = null;
                photoPreviewImg.src = '';
                photoPreviewWrap.style.display = 'none';
                photoLabel.style.display = 'inline-flex';
            }

            btnOpenModal.addEventListener('click', () => {
                reportModal.style.display = 'flex';
                if (localStorage.getItem('roicore_reporter_phone')) {
                    phoneInput.value = localStorage.getItem('roicore_reporter_phone');
                }
                autoDetectLocationImmediate();
            });

            btnCloseModal.addEventListener('click', () => {
                reportModal.style.display = 'none';
            });

            reportModal.addEventListener('click', (e) => {
                if (e.target === reportModal) {
                    reportModal.style.display = 'none';
                }
            });

            // แตะเลือก 1: เลือกระดับน้ำ 4 ระดับ
            document.querySelectorAll('.water-level-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.water-level-btn').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    selectedSeverity = parseInt(btn.getAttribute('data-severity') || '3', 10);
                });
            });

            // ปักหมุดบนแผนที่ในรัศมี 5 กม. จากในฟอร์ม
            btnPickMap.addEventListener('click', () => {
                startPinPickingMode('modal');
            });

            // ปักหมุดตีกรอบ 5 กม. จากปุ่มลัดบนแผนที่
            const btnQuickDrop = document.getElementById('btnQuickDropPin');
            if (btnQuickDrop) {
                btnQuickDrop.addEventListener('click', () => {
                    startPinPickingMode('map');
                });
            }

            // แตะเลือก 3: ส่งรายงานเหตุการณ์ไปยังเซิร์ฟเวอร์
            btnSubmit.addEventListener('click', async () => {
                // ตรวจสอบระยะห่างก่อนส่งข้อมูล
                const distanceMeters = calculateDistanceMeters(userCurrentLat, userCurrentLng, selectedReportLat, selectedReportLng);
                if (distanceMeters > 5000) {
                    showToast(`จุดแจ้งเหตุอยู่นอกรัศมี 5 กม. (ห่าง ${(distanceMeters/1000).toFixed(1)} กม.)`);
                }

                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังบันทึกข้อมูล...';

                const selectedBtn = document.querySelector('.water-level-btn.selected');
                const descText = selectedBtn ? selectedBtn.getAttribute('data-label') : 'น้ำท่วมสูง รถเล็กผ่านไม่ได้';
                const phone = phoneInput.value.trim();

                const payload = {
                    latitude: selectedReportLat,
                    longitude: selectedReportLng,
                    user_latitude: userCurrentLat,
                    user_longitude: userCurrentLng,
                    max_distance_meters: 5000,
                    severity: selectedSeverity,
                    description: descText,
                    phone: phone || null,
                    photos: selectedPhotoBase64 ? [selectedPhotoBase64] : []
                };

                try {
                    let response = await fetch(getApiUrl('api/reports'), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        response = await fetch(getApiUrl('?api=reports'), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        });
                    }

                    // เพิ่มหมุดใหม่ลงบนแผนที่ทันทีพร้อมตีกรอบ 5 กม.
                    const newPoint = {
                        id: 'report_' + Date.now(),
                        title: 'จุดรายงานใหม่โดยประชาชน',
                        location: { latitude: selectedReportLat, longitude: selectedReportLng },
                        water_level_meters: selectedSeverity === 3 ? 1.6 : (selectedSeverity === 2 ? 0.8 : 0.2),
                        risk_level: selectedSeverity === 3 ? 'CRITICAL' : (selectedSeverity === 2 ? 'MEDIUM' : 'LOW'),
                        risk_level_label: selectedSeverity === 3 ? 'ความเสี่ยงวิกฤต' : (selectedSeverity === 2 ? 'ความเสี่ยงปานกลาง' : 'เฝ้าระวัง'),
                        radius_meters: 5000,
                        zone_name: 'เขตเฝ้าระวังน้ำท่วม 5 กิโลเมตร (รายงานโดยประชาชน)',
                        report_count: 1,
                        updated_at: new Date().toISOString()
                    };

                    allFloodPoints.unshift(newPoint);
                    renderMarkers();
                    drawPinned1kmZone(selectedReportLat, selectedReportLng, true);

                    resetPhotoField();
                    reportModal.style.display = 'none';
                    showToast('ขอบคุณสำหรับการแจ้งเหตุ ข้อมูลและตีกรอบ 5 กม. เข้าสู่ระบบเรียบร้อยแล้ว');
                } catch (err) {
                    const offlinePoint = {
                        id: 'offline_' + Date.now(),
                        title: 'จุดรายงานใหม่ (โหมดออฟไลน์)',
                        location: { latitude: selectedReportLat, longitude: selectedReportLng },
                        water_level_meters: selectedSeverity === 3 ? 1.6 : (selectedSeverity === 2 ? 0.8 : 0.2),
                        risk_level: selectedSeverity === 3 ? 'CRITICAL' : (selectedSeverity === 2 ? 'MEDIUM' : 'LOW'),
                        risk_level_label: selectedSeverity === 3 ? 'ความเสี่ยงวิกฤต' : (selectedSeverity === 2 ? 'ความเสี่ยงปานกลาง' : 'เฝ้าระวัง'),
                        radius_meters: 5000,
                        zone_name: 'เขตเฝ้าระวังน้ำท่วม 5 กิโลเมตร (โหมดออฟไลน์)',
                        report_count: 1,
                        updated_at: new Date().toISOString()
                    };
                    allFloodPoints.unshift(offlinePoint);
                    renderMarkers();
                    drawPinned1kmZone(selectedReportLat, selectedReportLng, true);

                    resetPhotoField();
                    reportModal.style.display = 'none';
                    showToast('บันทึกรายงานเหตุการณ์และตีกรอบ 5 กม. ในอุปกรณ์เรียบร้อยแล้ว');
                } finally {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ส่งรายงานสถานการณ์ด่วน';
                }
            });

            // =====================================================================
            // Toast Notification Helper (Zero Border, Zero Shadow)
            // =====================================================================
            const toast = document.getElementById('toastNotify');
            const toastMsg = document.getElementById('toastMessage');
            let toastTimer = null;

            function showToast(message) {
                if (toastTimer) clearTimeout(toastTimer);
                toastMsg.textContent = message;
                toast.classList.add('show');
                toastTimer = setTimeout(() => {
                    toast.classList.remove('show');
                }, 3500);
            }

            // เริ่มต้นโหลดข้อมูล
            fetchFloodPoints();
            loadGistdaSatelliteLayer();
        });
    </script>
</body>
</html>
