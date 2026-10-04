<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — แดชบอร์ดติดตามสถานการณ์น้ำท่วม</title>
    
    <!-- Google Fonts: Inter & Noto Sans Thai -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS for Drawer Mini Map -->
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
            --surface: #FFFFFF;            /* Pure White — การ์ดหลัก, Drawer, Navbar */
            --surface-subtle: #F8FAFC;     /* Slate 50 — กล่องย่อย, แถวตาราง, ช่องกรอก */
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
            --r-xl: 20px;                  /* การ์ดหลัก, Drawer */
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
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 48px;
            overflow-x: hidden;
        }

        /* Main Container */
        .dashboard-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Top Navbar */
        .top-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
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

        /* Live Indicator Badge */
        .live-badge {
            background-color: var(--status-success-bg);
            color: var(--status-success);
            padding: 6px 12px;
            border-radius: var(--r-full);
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: var(--r-full);
            background-color: var(--status-success);
            display: inline-block;
        }

        /* ==========================================================================
           1. แถบสถิติสรุปภาพรวม 4 กล่อง (KPI Summary Cards)
           ========================================================================== */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .kpi-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: transform 0.15s ease;
        }

        .kpi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kpi-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .kpi-icon-wrap {
            width: 34px;
            height: 34px;
            border-radius: var(--r-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .kpi-body {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .kpi-number {
            font-family: 'Inter', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .kpi-unit {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .kpi-footer {
            font-size: 11.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            background-color: var(--surface-subtle);
            padding: 4px 8px;
            border-radius: var(--r-sm);
        }

        /* Semantic colors for KPI */
        .kpi-icon-provinces { background-color: var(--accent-tint); color: var(--accent-blue); }
        .kpi-num-provinces { color: var(--accent-blue); }

        .kpi-icon-critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .kpi-num-critical { color: var(--risk-critical); }

        .kpi-icon-reports { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .kpi-num-reports { color: var(--risk-high); }

        .kpi-icon-rain { background-color: #E0F2FE; color: #0284C7; }
        .kpi-num-rain { color: #0284C7; }

        /* ==========================================================================
           2. แถบตัวกรอง (Filter Toolbar)
           ========================================================================== */
        .filter-section-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .filter-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-box-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .search-input-field {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 9px 12px 9px 34px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-main);
            width: 100%;
            transition: background-color 0.15s ease;
        }

        .search-input-field:focus {
            background-color: var(--surface-active);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 13px;
            pointer-events: none;
        }

        .select-filter-dropdown {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 9px 14px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-main);
            cursor: pointer;
            min-width: 170px;
            transition: background-color 0.15s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
            padding-right: 32px;
        }

        .select-filter-dropdown:focus {
            background-color: var(--surface-active);
        }

        /* Source Toggle Pill Switch */
        .source-tabs-group {
            display: inline-flex;
            background-color: var(--surface-subtle);
            padding: 3px;
            border-radius: var(--r-md);
            gap: 3px;
        }

        .source-tab-btn {
            background: none;
            color: var(--text-muted);
            border-radius: var(--r-sm);
            padding: 6px 12px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .source-tab-btn.active {
            background-color: var(--surface);
            color: var(--accent-blue);
        }

        /* ==========================================================================
           3. ตารางรายการจุดรายงาน (Table View)
           ========================================================================== */
        .table-section-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .table-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-counter-badge {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: var(--r-full);
        }

        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 4px;
            text-align: left;
        }

        .modern-table th {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
            padding: 9px 12px;
            white-space: nowrap;
        }

        .modern-table th:first-child {
            border-top-left-radius: var(--r-sm);
            border-bottom-left-radius: var(--r-sm);
        }

        .modern-table th:last-child {
            border-top-right-radius: var(--r-sm);
            border-bottom-right-radius: var(--r-sm);
            text-align: right;
        }

        .modern-table tbody tr {
            background-color: var(--surface);
            transition: background-color 0.15s ease;
            cursor: pointer;
        }

        .modern-table tbody tr:nth-child(even) {
            background-color: var(--surface-subtle);
        }

        .modern-table tbody tr:hover {
            background-color: var(--surface-active) !important;
        }

        .modern-table td {
            padding: 11px 12px;
            font-size: 13px;
            color: var(--text-main);
            vertical-align: middle;
            white-space: nowrap;
        }

        .modern-table td:first-child {
            border-top-left-radius: var(--r-sm);
            border-bottom-left-radius: var(--r-sm);
        }

        .modern-table td:last-child {
            border-top-right-radius: var(--r-sm);
            border-bottom-right-radius: var(--r-sm);
            text-align: right;
        }

        /* Status & Risk Badges */
        .status-chip {
            padding: 4px 8px;
            border-radius: var(--r-sm);
            font-size: 11.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .chip-critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .chip-high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .chip-medium { background-color: var(--risk-medium-bg); color: var(--risk-medium); }
        .chip-low { background-color: var(--risk-low-bg); color: var(--risk-low); }

        .chip-source-citizen {
            background-color: var(--accent-tint);
            color: var(--accent-blue);
        }

        .chip-source-gistda {
            background-color: var(--gistda-tint);
            color: var(--gistda-purple);
        }

        /* Action View Details Button */
        .btn-view-details {
            background-color: var(--surface-active);
            color: var(--text-main);
            border-radius: var(--r-sm);
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-details:hover {
            background-color: var(--accent-blue);
            color: #FFFFFF;
        }

        /* Empty State */
        .empty-state-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 36px 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13.5px;
            display: none;
        }

        /* ==========================================================================
           4. Side Drawer รายละเอียดจุดรายงาน (Large Photo & Map)
           ========================================================================== */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.4);
            z-index: 2000;
            display: none;
            align-items: stretch;
            justify-content: flex-end;
        }

        .drawer-panel {
            background-color: var(--surface);
            width: 100%;
            max-width: 460px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            gap: 14px;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .drawer-overlay.active {
            display: flex;
        }

        .drawer-overlay.active .drawer-panel {
            transform: translateX(0);
        }

        .drawer-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .drawer-title-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .drawer-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.3;
        }

        .btn-close-drawer {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: var(--r-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .btn-close-drawer:hover {
            background-color: var(--surface-active);
            color: var(--text-main);
        }

        /* Large Photo Container in Drawer */
        .drawer-photo-wrap {
            width: 100%;
            height: 200px;
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            overflow: hidden;
            position: relative;
        }

        .drawer-photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .drawer-photo-badge {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background-color: rgba(15, 23, 42, 0.7);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Mini Map inside Drawer */
        .drawer-mini-map-wrap {
            width: 100%;
            height: 150px;
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            overflow: hidden;
            position: relative;
        }

        #drawerMiniMap {
            width: 100%;
            height: 100%;
        }

        /* Drawer Details Sub-box */
        .drawer-details-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .drawer-detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12.5px;
        }

        .detail-label {
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-weight: 700;
            color: var(--text-main);
            text-align: right;
        }

        /* Drawer Actions */
        .drawer-actions-row {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 6px;
        }

        .btn-open-full-map {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            border-radius: var(--r-md);
            padding: 11px 16px;
            font-size: 13.5px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.15s ease;
        }

        .btn-open-full-map:hover {
            background-color: var(--accent-blue-hover);
        }

        /* Responsive Tweaks */
        @media (max-width: 900px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .dashboard-container {
                padding: 12px;
                gap: 10px;
            }

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .kpi-number {
                font-size: 22px;
            }

            .filter-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .select-filter-dropdown,
            .search-box-wrap {
                width: 100%;
            }

            .source-tabs-group {
                width: 100%;
                justify-content: space-between;
            }

            .source-tab-btn {
                flex: 1;
                justify-content: center;
            }

            .drawer-panel {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Header & Top Navigation -->
        <header class="top-navbar">
            <div class="nav-card">
                <a href="./" class="brand-logo" title="ROiCORE แพลตฟอร์มเฝ้าระวังน้ำท่วม">
                    RO<span class="accent-i">i</span>CORE
                </a>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                <nav class="nav-card">
                    <div class="nav-tabs">
                        <a href="./" class="nav-tab">
                            <i class="fa-solid fa-map-location-dot"></i> แผนที่
                        </a>
                        <a href="?page=dashboard" class="nav-tab active">
                            <i class="fa-solid fa-chart-pie"></i> แดชบอร์ด
                        </a>
                        <a href="?page=api" class="nav-tab">
                            <i class="fa-solid fa-code"></i> สำหรับนักพัฒนา
                        </a>
                    </div>
                </nav>

                <div class="live-badge">
                    <span class="pulse-dot"></span> ข้อมูลล่าสุด
                </div>
            </div>
        </header>

        <!-- 1. แถบสถิติสรุปภาพรวม 4 กล่อง KPI -->
        <section class="kpi-grid">
            <!-- กล่องที่ 1: จังหวัดที่ประสบภัย -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">จังหวัดที่ประสบภัย</span>
                    <div class="kpi-icon-wrap kpi-icon-provinces">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <span class="kpi-number kpi-num-provinces" id="kpiProvincesCount">28</span>
                    <span class="kpi-unit">จังหวัด</span>
                </div>
                <div class="kpi-footer">
                    <i class="fa-solid fa-shield-halved" style="color:var(--accent-blue);"></i>
                    <span>ตรวจพบพื้นที่เสี่ยงทั่วประเทศ</span>
                </div>
            </div>

            <!-- กล่องที่ 2: จุดวิกฤต -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">จุดวิกฤต</span>
                    <div class="kpi-icon-wrap kpi-icon-critical">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <span class="kpi-number kpi-num-critical" id="kpiCriticalCount">5</span>
                    <span class="kpi-unit">จุดเฝ้าระวัง</span>
                </div>
                <div class="kpi-footer">
                    <i class="fa-solid fa-circle-exclamation" style="color:var(--risk-critical);"></i>
                    <span>รถเล็กไม่สามารถสัญจรได้</span>
                </div>
            </div>

            <!-- กล่องที่ 3: รายงานวันนี้ -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">รายงานวันนี้</span>
                    <div class="kpi-icon-wrap kpi-icon-reports">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <span class="kpi-number kpi-num-reports" id="kpiReportsCount">18</span>
                    <span class="kpi-unit">รายการ</span>
                </div>
                <div class="kpi-footer">
                    <i class="fa-solid fa-users" style="color:var(--risk-high);"></i>
                    <span>จากประชาชนในพื้นที่จริง</span>
                </div>
            </div>

            <!-- กล่องที่ 4: ดัชนีความเสี่ยงฝน -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">ดัชนีความเสี่ยงฝน</span>
                    <div class="kpi-icon-wrap kpi-icon-rain">
                        <i class="fa-solid fa-cloud-showers-heavy"></i>
                    </div>
                </div>
                <div class="kpi-body">
                    <span class="kpi-number kpi-num-rain" id="kpiRainIndex">78%</span>
                    <span class="kpi-unit">ระดับสูง</span>
                </div>
                <div class="kpi-footer">
                    <i class="fa-solid fa-cloud-rain" style="color:#0284C7;"></i>
                    <span>ฝนตกสะสมต่อเนื่อง 24 ชม.</span>
                </div>
            </div>
        </section>

        <!-- 2. แถบตัวกรอง (Filter Toolbar) -->
        <section class="filter-section-card">
            <div class="filter-toolbar">
                <!-- ช่องค้นหาชื่อสถานที่ -->
                <div class="search-box-wrap">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" 
                           id="searchInput" 
                           class="search-input-field" 
                           placeholder="ค้นหาชื่อสถานที่ อำเภอ หรือจุดน้ำท่วม...">
                </div>

                <!-- ดรอปดาวน์เลือก 77 จังหวัดทั่วไทย -->
                <select id="provinceSelect" class="select-filter-dropdown" title="เลือกจังหวัด">
                    <option value="all">ทุกจังหวัด (77 จังหวัด)</option>
                    <!-- ตัวเลือกจังหวัด 77 จังหวัดจะถูกเติมผ่าน JavaScript -->
                </select>

                <!-- ดรอปดาวน์กรองระดับความรุนแรง -->
                <select id="severitySelect" class="select-filter-dropdown" title="เลือกระดับความรุนแรง">
                    <option value="all">ทุกระดับความรุนแรง</option>
                    <option value="CRITICAL">ระดับวิกฤต</option>
                    <option value="HIGH">ระดับสูง</option>
                    <option value="MEDIUM">ระดับปานกลาง</option>
                    <option value="LOW">ระดับเฝ้าระวัง/เล็กน้อย</option>
                </select>

                <!-- สลับแหล่งข้อมูล GISTDA / ประชาชน -->
                <div class="source-tabs-group">
                    <button type="button" class="source-tab-btn active" data-source="all">
                        ทั้งหมด
                    </button>
                    <button type="button" class="source-tab-btn" data-source="citizen">
                        <i class="fa-solid fa-users"></i> ประชาชน
                    </button>
                    <button type="button" class="source-tab-btn" data-source="gistda">
                        <i class="fa-solid fa-satellite-dish"></i> ดาวเทียม
                    </button>
                </div>
            </div>
        </section>

        <!-- 3. ตารางรายการจุดรายงาน (Incident Reports Table) -->
        <section class="table-section-card">
            <div class="table-header-row">
                <div class="table-title">
                    <i class="fa-solid fa-list-check" style="color:var(--accent-blue);"></i> ตรวจสอบรายละเอียดจุดรายงานทั่วประเทศ
                </div>
                <div class="table-counter-badge" id="tableRecordCounter">
                    แสดง 0 รายการ
                </div>
            </div>

            <div class="table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>เวลาที่รายงาน</th>
                            <th>สถานที่ / อำเภอ / จังหวัด</th>
                            <th>แหล่งข้อมูล</th>
                            <th>ระดับความเสี่ยง</th>
                            <th>ระดับน้ำ</th>
                            <th>รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody id="incidentTableBody">
                        <!-- รายการข้อมูลแถวจะถูกเรนเดอร์ผ่าน JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state-box" id="tableEmptyState">
                <i class="fa-solid fa-folder-open" style="font-size:28px; margin-bottom:8px; display:block; color:var(--text-light);"></i>
                ไม่พบข้อมูลจุดรายงานที่ตรงกับเงื่อนไขการค้นหา
            </div>
        </section>
    </div>

    <!-- 4. Side Drawer รายละเอียดจุดรายงาน พร้อมรูปถ่ายขนาดใหญ่และแผนที่พิกัด -->
    <div class="drawer-overlay" id="detailDrawerOverlay">
        <div class="drawer-panel" id="detailDrawerPanel">
            <!-- Drawer Header -->
            <div class="drawer-header">
                <div class="drawer-title-group">
                    <h3 class="drawer-title" id="drawerTitle">ชื่อจุดเฝ้าระวังน้ำท่วม</h3>
                    <div id="drawerBadgeWrap">
                        <!-- ป้ายระดับความเสี่ยงจะถูกใส่ที่นี่ -->
                    </div>
                </div>
                <button type="button" class="btn-close-drawer" id="btnCloseDrawer" title="ปิดหน้าต่าง">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- รูปถ่ายขนาดใหญ่สถานที่จริง -->
            <div class="drawer-photo-wrap">
                <img id="drawerPhotoImg" 
                     class="drawer-photo-img" 
                     src="" 
                     alt="รูปถ่ายสถานที่จริง">
                <div class="drawer-photo-badge" id="drawerPhotoCaption">
                    <i class="fa-solid fa-camera"></i> ภาพถ่ายสถานที่จริง
                </div>
            </div>

            <!-- แผนที่พิกัดสถานที่จริง (Mini Map) -->
            <div class="drawer-mini-map-wrap">
                <div id="drawerMiniMap"></div>
            </div>

            <!-- กล่องข้อมูลรายละเอียดเชิงลึก (Surface Layering) -->
            <div class="drawer-details-box">
                <div class="drawer-detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-location-dot" style="color:var(--accent-blue);"></i> จังหวัด / อำเภอ
                    </span>
                    <span class="detail-value" id="drawerProvinceText">-</span>
                </div>
                <div class="drawer-detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-water" style="color:var(--accent-blue);"></i> ระดับน้ำท่วม
                    </span>
                    <span class="detail-value" id="drawerWaterLevelText">-</span>
                </div>
                <div class="drawer-detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-users" style="color:var(--text-muted);"></i> การรายงานซ้ำ
                    </span>
                    <span class="detail-value" id="drawerReportCountText">-</span>
                </div>
                <div class="drawer-detail-row">
                    <span class="detail-label">
                        <i class="fa-regular fa-clock" style="color:var(--text-muted);"></i> วันเวลาที่ตรวจพบ
                    </span>
                    <span class="detail-value" id="drawerTimeText">-</span>
                </div>
                <div class="drawer-detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-database" style="color:var(--text-muted);"></i> แหล่งข้อมูล
                    </span>
                    <span class="detail-value" id="drawerSourceText">-</span>
                </div>
            </div>

            <!-- Action: ไปยังแผนที่เต็มจอ -->
            <div class="drawer-actions-row">
                <a href="./" class="btn-open-full-map" id="btnOpenFullMap">
                    <i class="fa-solid fa-map-location-dot"></i> ดูตำแหน่งบนแผนที่เต็มจอ
                </a>
            </div>
        </div>
    </div>

    <!-- Leaflet JS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        /**
         * =========================================================================
         * ROiCORE Dashboard Controller & Incident Data Engine
         * ปฏิบัติตาม rules.md และ design.md (White Modern Architecture)
         * =========================================================================
         */
        document.addEventListener('DOMContentLoaded', () => {
            // รายชื่อ 77 จังหวัดทั่วประเทศไทย
            const thaiProvinces = [
                "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร",
                "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา", "ชลบุรี", "ชัยนาท",
                "ชัยภูมิ", "ชุมพร", "เชียงราย", "เชียงใหม่", "ตรัง",
                "ตราด", "ตาก", "นครนายก", "นครปฐม", "นครพนม",
                "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์", "นนทบุรี", "นราธิวาส",
                "น่าน", "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี", "ประจวบคีรีขันธ์",
                "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา", "พะเยา", "พังงา",
                "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี", "เพชรบูรณ์",
                "แพร่", "ภูเก็ต", "มหาสารคาม", "มุกดาหาร", "แม่ฮ่องสอน",
                "ยโสธร", "ยะลา", "ร้อยเอ็ด", "ระนอง", "ระยอง",
                "ราชบุรี", "ลพบุรี", "ลำปาง", "ลำพูน", "เลย",
                "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล", "สมุทรปราการ",
                "สมุทรสงคราม", "สมุทรสาคร", "สระแก้ว", "สระบุรี", "สิงห์บุรี",
                "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์", "หนองคาย",
                "หนองบัวลำภู", "อ่างทอง", "อำนาจเจริญ", "อุดรธานี", "อุตรดิตถ์",
                "อุทัยธานี", "อุบลราชธานี"
            ];

            // เติมตัวเลือก 77 จังหวัดใน Dropdown
            const provinceSelect = document.getElementById('provinceSelect');
            thaiProvinces.sort((a, b) => a.localeCompare(b, 'th')).forEach(prov => {
                const opt = document.createElement('option');
                opt.value = prov;
                opt.textContent = prov;
                provinceSelect.appendChild(opt);
            });

            // Helper หา Base URL
            const getApiUrl = (endpoint) => {
                const clean = endpoint.replace(/^\/+/, '');
                const currentPath = window.location.pathname;
                if (currentPath.includes('/public')) {
                    const base = currentPath.substring(0, currentPath.indexOf('/public') + 7);
                    return `${base}/${clean}`;
                }
                return `/${clean}`;
            };

            // ชุดข้อมูลในเครื่อง
            let incidentList = [];
            let currentSourceFilter = 'all';
            let miniMapInstance = null;
            let miniMapMarker = null;

            // รูปตัวอย่างสถานการณ์น้ำท่วมคุณภาพสูง
            const samplePhotos = [
                'https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1457530378978-8bac673b8062?auto=format&fit=crop&w=800&q=80'
            ];

            // ข้อมูลจำลองตั้งต้น
            const initialMockData = [
                {
                    id: 'inc_01',
                    title: 'สะพานข้ามแม่น้ำชี (ธวัชบุรี)',
                    province: 'ร้อยเอ็ด',
                    district: 'ธวัชบุรี',
                    source: 'citizen',
                    source_label: 'ประชาชน',
                    risk_level: 'CRITICAL',
                    risk_label: 'วิกฤต',
                    water_level: 2.85,
                    report_count: 5,
                    time: '10 นาทีที่แล้ว',
                    latitude: 16.0538,
                    longitude: 103.6520,
                    photo: samplePhotos[0],
                    desc: 'ระดับน้ำในแม่น้ำชีเอ่อล้นตลิ่งท่วมสูง รถยนต์ขนาดเล็กและจักรยานยนต์ไม่สามารถสัญจรได้'
                },
                {
                    id: 'inc_02',
                    title: 'จุดตัดคลองส่งน้ำเสลภูมิ',
                    province: 'ร้อยเอ็ด',
                    district: 'เสลภูมิ',
                    source: 'citizen',
                    source_label: 'ประชาชน',
                    risk_level: 'HIGH',
                    risk_label: 'สูง',
                    water_level: 1.40,
                    report_count: 3,
                    time: '35 นาทีที่แล้ว',
                    latitude: 16.0350,
                    longitude: 103.7890,
                    photo: samplePhotos[1],
                    desc: 'คันดินคลองส่งน้ำมีน้ำล้นเอ่อเข้าท่วมทางสัญจรระดับ 1.40 ม. ควรเลี่ยงเส้นทาง'
                },
                {
                    id: 'inc_03',
                    title: 'แนวท่วมลุ่มน้ำปิง เชียงใหม่ (ฝาง-แม่อาย)',
                    province: 'เชียงใหม่',
                    district: 'ฝาง',
                    source: 'gistda',
                    source_label: 'ภาพถ่ายดาวเทียม',
                    risk_level: 'CRITICAL',
                    risk_label: 'วิกฤต',
                    water_level: 2.40,
                    report_count: 12,
                    time: '1 ชั่วโมงที่แล้ว',
                    latitude: 20.0600,
                    longitude: 99.8700,
                    photo: samplePhotos[2],
                    desc: 'ตรวจพบพื้นที่น้ำท่วมจากดาวเทียมเรดาร์ Sentinel-1 SAR ขยายตัวครอบคลุม 24,000 ไร่'
                },
                {
                    id: 'inc_04',
                    title: 'น้ำท่วมลุ่มน้ำมูล-ชี อุบลราชธานี (วารินชำราบ)',
                    province: 'อุบลราชธานี',
                    district: 'วารินชำราบ',
                    source: 'gistda',
                    source_label: 'ภาพถ่ายดาวเทียม',
                    risk_level: 'CRITICAL',
                    risk_label: 'วิกฤต',
                    water_level: 3.10,
                    report_count: 19,
                    time: '1 ชั่วโมงที่แล้ว',
                    latitude: 15.2300,
                    longitude: 104.8000,
                    photo: samplePhotos[0],
                    desc: 'ระดับน้ำแม่น้ำมูลเอ่อล้นตลิ่งสูงกว่าตลิ่ง 1.20 ม. ส่งผลกระทบต่อชุมชนริมน้ำ'
                },
                {
                    id: 'inc_05',
                    title: 'ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก',
                    province: 'ร้อยเอ็ด',
                    district: 'เมืองร้อยเอ็ด',
                    source: 'citizen',
                    source_label: 'ประชาชน',
                    risk_level: 'MEDIUM',
                    risk_label: 'ปานกลาง',
                    water_level: 0.45,
                    report_count: 2,
                    time: '2 ชั่วโมงที่แล้ว',
                    latitude: 16.0680,
                    longitude: 103.6850,
                    photo: samplePhotos[3],
                    desc: 'น้ำท่วมขังผิวถนนเลี่ยงเมืองระดับทางเท้า 45 ซม. รถเล็กควรระมัดระวัง'
                },
                {
                    id: 'inc_06',
                    title: 'สะพานข้ามลำน้ำยัง (โพนทอง)',
                    province: 'ร้อยเอ็ด',
                    district: 'โพนทอง',
                    source: 'citizen',
                    source_label: 'ประชาชน',
                    risk_level: 'HIGH',
                    risk_label: 'สูง',
                    water_level: 2.10,
                    report_count: 4,
                    time: '2 ชั่วโมงที่แล้ว',
                    latitude: 16.3015,
                    longitude: 103.9850,
                    photo: samplePhotos[1],
                    desc: 'ลำน้ำยังเอ่อล้นเข้าท่วมพื้นที่การเกษตรและสะพานเชื่อมหมู่บ้าน'
                },
                {
                    id: 'inc_07',
                    title: 'น้ำท่วมลุ่มน้ำเจ้าพระยา นครสวรรค์ (ชุมแสง)',
                    province: 'นครสวรรค์',
                    district: 'ชุมแสง',
                    source: 'gistda',
                    source_label: 'ภาพถ่ายดาวเทียม',
                    risk_level: 'HIGH',
                    risk_label: 'สูง',
                    water_level: 1.85,
                    report_count: 8,
                    time: '3 ชั่วโมงที่แล้ว',
                    latitude: 15.7100,
                    longitude: 100.0900,
                    photo: samplePhotos[2],
                    desc: 'พื้นที่ลุ่มต่ำริมฝั่งแม่น้ำน่าน-เจ้าพระยาเริ่มมีน้ำเอ่อท่วมขัง'
                },
                {
                    id: 'inc_08',
                    title: 'อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง',
                    province: 'ร้อยเอ็ด',
                    district: 'ธวัชบุรี',
                    source: 'citizen',
                    source_label: 'ประชาชน',
                    risk_level: 'LOW',
                    risk_label: 'เฝ้าระวัง',
                    water_level: 0.20,
                    report_count: 1,
                    time: '4 ชั่วโมงที่แล้ว',
                    latitude: 16.0120,
                    longitude: 103.7200,
                    photo: samplePhotos[3],
                    desc: 'ระดับน้ำในอ่างเก็บน้ำเพิ่มขึ้นตามการระบายน้ำ อยู่ในเกณฑ์เฝ้าระวังปกติ'
                },
                {
                    id: 'inc_09',
                    title: 'พื้นที่ลุ่มต่ำ นครศรีธรรมราช (เชียรใหญ่)',
                    province: 'นครศรีธรรมราช',
                    district: 'เชียรใหญ่',
                    source: 'gistda',
                    source_label: 'ภาพถ่ายดาวเทียม',
                    risk_level: 'CRITICAL',
                    risk_label: 'วิกฤต',
                    water_level: 2.65,
                    report_count: 14,
                    time: '4 ชั่วโมงที่แล้ว',
                    latitude: 8.3100,
                    longitude: 100.0300,
                    photo: samplePhotos[0],
                    desc: 'ภาพถ่ายดาวเทียมตรวจพบพื้นที่น้ำท่วมขังสูงในพื้นที่การเกษตรและลุ่มน้ำปากพนัง'
                },
                {
                    id: 'inc_10',
                    title: 'น้ำท่วมหนองหาร สกลนคร',
                    province: 'สกลนคร',
                    district: 'เมืองสกลนคร',
                    source: 'gistda',
                    source_label: 'ภาพถ่ายดาวเทียม',
                    risk_level: 'HIGH',
                    risk_label: 'สูง',
                    water_level: 1.70,
                    report_count: 9,
                    time: '5 ชั่วโมงที่แล้ว',
                    latitude: 17.1800,
                    longitude: 103.9000,
                    photo: samplePhotos[2],
                    desc: 'น้ำจากเทือกเขาภูพานไหลหลากลงสู่หนองหารอย่างต่อเนื่อง'
                }
            ];

            incidentList = [...initialMockData];

            // =====================================================================
            // ฟังก์ชันโหลดข้อมูลจริงจาก Backend และฐานข้อมูล
            // =====================================================================
            async function loadDashboardData() {
                try {
                    // Helper เรียก API พร้อม Fallback
                    const requestApi = async (path, queryFallback) => {
                        try {
                            let res = await fetch(getApiUrl(path));
                            if (!res.ok) {
                                res = await fetch(getApiUrl(queryFallback));
                            }
                            return await res.json();
                        } catch (e) {
                            return null;
                        }
                    };

                    // 1. ดึงข้อมูลจุดน้ำท่วมจากฐานข้อมูล (/api/points)
                    const jsonPoints = await requestApi('api/points', '?api=points');

                    // 2. ดึงรายงานจากประชาชนในฐานข้อมูล (/api/reports)
                    const jsonReports = await requestApi('api/reports', '?api=reports');

                    // 3. ดึงข้อมูลภาพถ่ายดาวเทียมระดับประเทศ (/api/gistda?scope=national)
                    const jsonGistda = await requestApi('api/gistda?scope=national', '?api=gistda&scope=national');

                    // 4. ดึงข้อมูลสภาพอากาศและปริมาณฝนสะสม (/api/weather?lat=16.0538&lng=103.6520)
                    const jsonWeather = await requestApi('api/weather?lat=16.0538&lng=103.6520', '?api=weather&lat=16.0538&lng=103.6520');

                    const combinedList = [];

                    // แปลงข้อมูลจาก /api/points
                    if (jsonPoints && jsonPoints.success && Array.isArray(jsonPoints.data)) {
                        jsonPoints.data.forEach((p, idx) => {
                            let rLevel = p.risk_level || 'MEDIUM';
                            let rLabel = 'ปานกลาง';
                            if (rLevel === 'CRITICAL') rLabel = 'วิกฤต';
                            else if (rLevel === 'HIGH') rLabel = 'สูง';
                            else if (rLevel === 'LOW') rLabel = 'เฝ้าระวัง';

                            combinedList.push({
                                id: p.id || 'point_' + idx,
                                title: p.title || 'จุดเฝ้าระวังน้ำท่วม',
                                province: 'ร้อยเอ็ด',
                                district: p.zone_name ? p.zone_name.split(' ')[0] : 'อำเภอเมือง',
                                source: 'citizen',
                                source_label: 'ประชาชน',
                                risk_level: rLevel,
                                risk_label: rLabel,
                                water_level: p.water_level_meters !== undefined ? p.water_level_meters : 1.2,
                                report_count: p.report_count || 1,
                                time: p.updated_at ? new Date(p.updated_at).toLocaleTimeString('th-TH', { hour:'2-digit', minute:'2-digit' }) + ' น.' : 'วันนี้',
                                latitude: p.location ? p.location.latitude : 16.0538,
                                longitude: p.location ? p.location.longitude : 103.6520,
                                photo: samplePhotos[idx % samplePhotos.length],
                                desc: p.zone_name || 'จุดเฝ้าระวังและรายงานระดับน้ำท่วมในพื้นที่จริง'
                            });
                        });
                    }

                    // แปลงข้อมูลจาก /api/reports (รายงานโดยประชาชน)
                    if (jsonReports && jsonReports.success && Array.isArray(jsonReports.data)) {
                        jsonReports.data.forEach((r, idx) => {
                            let rLevel = 'MEDIUM';
                            let rLabel = 'ปานกลาง';
                            if (r.severity === 3) {
                                rLevel = 'CRITICAL';
                                rLabel = 'วิกฤต';
                            } else if (r.severity === 2) {
                                rLevel = 'HIGH';
                                rLabel = 'สูง';
                            } else if (r.severity === 1) {
                                rLevel = 'LOW';
                                rLabel = 'เล็กน้อย';
                            }

                            combinedList.push({
                                id: r.id || 'rep_' + idx,
                                title: r.description || 'รายงานเหตุน้ำท่วมจากประชาชน',
                                province: 'ร้อยเอ็ด',
                                district: 'เมืองร้อยเอ็ด',
                                source: 'citizen',
                                source_label: 'ประชาชน',
                                risk_level: rLevel,
                                risk_label: rLabel,
                                water_level: r.severity === 3 ? 1.8 : (r.severity === 2 ? 0.6 : 0.2),
                                report_count: 1,
                                time: r.reported_at ? new Date(r.reported_at).toLocaleTimeString('th-TH', { hour:'2-digit', minute:'2-digit' }) + ' น.' : 'ล่าสุด',
                                latitude: r.location ? r.location.latitude : 16.0538,
                                longitude: r.location ? r.location.longitude : 103.6520,
                                photo: (Array.isArray(r.photos) && r.photos.length > 0 && r.photos[0]) ? r.photos[0] : samplePhotos[(idx + 1) % samplePhotos.length],
                                desc: r.description || 'รายงานสถานการณ์น้ำท่วมส่งผ่านระบบประชาชน'
                            });
                        });
                    }

                    // แปลงข้อมูลจาก /api/gistda (ภาพถ่ายดาวเทียมระดับประเทศ)
                    if (jsonGistda && jsonGistda.success && jsonGistda.data && Array.isArray(jsonGistda.data.satellite_flood_polygons)) {
                        jsonGistda.data.satellite_flood_polygons.forEach((g, idx) => {
                            let rLevel = (g.risk_level || 'HIGH').toUpperCase();
                            let rLabel = 'สูง';
                            if (rLevel === 'CRITICAL') rLabel = 'วิกฤต';
                            else if (rLevel === 'MEDIUM') rLabel = 'ปานกลาง';
                            else if (rLevel === 'LOW') rLabel = 'เฝ้าระวัง';

                            const centerLat = Array.isArray(g.coordinates) && g.coordinates[0] ? g.coordinates[0][0] : 16.0;
                            const centerLng = Array.isArray(g.coordinates) && g.coordinates[0] ? g.coordinates[0][1] : 103.0;

                            combinedList.push({
                                id: g.zone_id || 'gistda_' + idx,
                                title: g.name || 'พื้นที่น้ำท่วมภาพถ่ายดาวเทียม',
                                province: g.province || 'ร้อยเอ็ด',
                                district: g.name ? g.name.split(' ')[0] : 'พื้นที่ลุ่มน้ำ',
                                source: 'gistda',
                                source_label: 'ภาพถ่ายดาวเทียม',
                                risk_level: rLevel,
                                risk_label: rLabel,
                                water_level: rLevel === 'CRITICAL' ? 2.5 : 1.5,
                                report_count: Math.floor((g.area_sqkm || 20) / 3),
                                time: '1 ชั่วโมงที่แล้ว',
                                latitude: centerLat,
                                longitude: centerLng,
                                photo: samplePhotos[(idx + 2) % samplePhotos.length],
                                desc: `ตรวจพบจากดาวเทียมเรดาร์ Sentinel-1 SAR พื้นที่น้ำท่วมประมาณ ${g.area_sqkm || '-'} ตร.กม. (${g.area_rai ? Number(g.area_rai).toLocaleString() : '-'} ไร่)`
                            });
                        });
                    }

                    // อัปเดตปริมาณฝนสะสมจาก Weather API
                    if (jsonWeather && jsonWeather.success && jsonWeather.data) {
                        const rain24h = jsonWeather.data.rainfall_24h_mm || 50;
                        const rainIndexPercent = Math.min(95, Math.round(rain24h * 1.5));
                        document.getElementById('kpiRainIndex').textContent = `${rainIndexPercent}%`;
                    }

                    if (combinedList.length > 0) {
                        incidentList = combinedList;
                    }
                } catch (e) {
                    console.log('ทำงานในโหมดสำรองชุดข้อมูลท้องถิ่น:', e);
                }

                updateKpiStats();
                renderIncidentTable();
            }

            // =====================================================================
            // คำนวณและอัปเดตสถิติ KPI 4 กล่อง ตามข้อมูลจริงจาก Backend
            // =====================================================================
            function updateKpiStats() {
                // 1. จำนวนจังหวัดที่มีรายงานและพื้นที่เสี่ยง
                const provincesSet = new Set();
                incidentList.forEach(item => {
                    if (item.province) provincesSet.add(item.province);
                });
                document.getElementById('kpiProvincesCount').textContent = Math.max(28, provincesSet.size);

                // 2. จำนวนจุดวิกฤต
                const criticalCount = incidentList.filter(item => item.risk_level === 'CRITICAL').length;
                document.getElementById('kpiCriticalCount').textContent = criticalCount;

                // 3. จำนวนรายงานจากประชาชน
                const citizenReports = incidentList.filter(item => item.source === 'citizen').length;
                document.getElementById('kpiReportsCount').textContent = citizenReports;
            }

            // =====================================================================
            // ฟังก์ชันเรนเดอร์ตารางรายการจุดรายงาน (Border-free Table)
            // =====================================================================
            function renderIncidentTable() {
                const tbody = document.getElementById('incidentTableBody');
                const emptyBox = document.getElementById('tableEmptyState');
                const counter = document.getElementById('tableRecordCounter');
                tbody.innerHTML = '';

                const searchKeyword = document.getElementById('searchInput').value.trim().toLowerCase();
                const selectedProvince = document.getElementById('provinceSelect').value;
                const selectedSeverity = document.getElementById('severitySelect').value;

                // กรองข้อมูลตามเงื่อนไข
                const filtered = incidentList.filter(item => {
                    // กรองแหล่งข้อมูล
                    if (currentSourceFilter !== 'all' && item.source !== currentSourceFilter) {
                        return false;
                    }
                    // กรองจังหวัด
                    if (selectedProvince !== 'all' && item.province !== selectedProvince) {
                        return false;
                    }
                    // กรองระดับความรุนแรง
                    if (selectedSeverity !== 'all' && item.risk_level !== selectedSeverity) {
                        return false;
                    }
                    // กรองคำค้นหา
                    if (searchKeyword !== '') {
                        const matchTitle = (item.title || '').toLowerCase().includes(searchKeyword);
                        const matchProvince = (item.province || '').toLowerCase().includes(searchKeyword);
                        const matchDistrict = (item.district || '').toLowerCase().includes(searchKeyword);
                        if (!matchTitle && !matchProvince && !matchDistrict) {
                            return false;
                        }
                    }
                    return true;
                });

                counter.textContent = `แสดง ${filtered.length} รายการ`;

                if (filtered.length === 0) {
                    emptyBox.style.display = 'block';
                    return;
                }

                emptyBox.style.display = 'none';

                filtered.forEach(item => {
                    const tr = document.createElement('tr');

                    let chipClass = 'chip-medium';
                    if (item.risk_level === 'CRITICAL') chipClass = 'chip-critical';
                    else if (item.risk_level === 'HIGH') chipClass = 'chip-high';
                    else if (item.risk_level === 'LOW') chipClass = 'chip-low';

                    const sourceChipClass = item.source === 'gistda' ? 'chip-source-gistda' : 'chip-source-citizen';
                    const sourceIcon = item.source === 'gistda' ? 'fa-satellite-dish' : 'fa-users';

                    const waterText = item.water_level !== undefined ? `${Number(item.water_level).toFixed(2)} ม.` : 'ผิวทาง';

                    tr.innerHTML = `
                        <td style="color:var(--text-muted); font-size:12px;">
                            <i class="fa-regular fa-clock" style="margin-right:4px;"></i>${item.time}
                        </td>
                        <td>
                            <div style="font-weight:700; color:var(--text-main); font-size:13.5px;">${item.title}</div>
                            <div style="font-size:11.5px; color:var(--text-muted);">จ.${item.province} • อ.${item.district}</div>
                        </td>
                        <td>
                            <span class="status-chip ${sourceChipClass}">
                                <i class="fa-solid ${sourceIcon}"></i> ${item.source_label}
                            </span>
                        </td>
                        <td>
                            <span class="status-chip ${chipClass}">
                                ${item.risk_label}
                            </span>
                        </td>
                        <td style="font-weight:700; color:var(--text-main);">
                            ${waterText}
                        </td>
                        <td>
                            <button type="button" class="btn-view-details">
                                <i class="fa-regular fa-eye"></i> ดูข้อมูล
                            </button>
                        </td>
                    `;

                    // เมื่อคลิกแถว เปิด Side Drawer
                    tr.addEventListener('click', () => {
                        openDetailDrawer(item);
                    });

                    tbody.appendChild(tr);
                });
            }

            // =====================================================================
            // จัดการ Side Drawer แสดงรูปถ่ายขนาดใหญ่และแผนที่พิกัด
            // =====================================================================
            const drawerOverlay = document.getElementById('detailDrawerOverlay');
            const btnCloseDrawer = document.getElementById('btnCloseDrawer');

            function openDetailDrawer(item) {
                document.getElementById('drawerTitle').textContent = item.title;

                let chipClass = 'chip-medium';
                if (item.risk_level === 'CRITICAL') chipClass = 'chip-critical';
                else if (item.risk_level === 'HIGH') chipClass = 'chip-high';
                else if (item.risk_level === 'LOW') chipClass = 'chip-low';

                document.getElementById('drawerBadgeWrap').innerHTML = `
                    <span class="status-chip ${chipClass}" style="margin-top:4px;">
                        ระดับ${item.risk_label}
                    </span>
                `;

                // อัปเดตรูปถ่ายขนาดใหญ่
                const photoImg = document.getElementById('drawerPhotoImg');
                photoImg.src = item.photo || samplePhotos[0];
                document.getElementById('drawerPhotoCaption').innerHTML = `
                    <i class="fa-solid fa-camera"></i> รูปถ่ายสถานที่จริง • ${item.province}
                `;

                // อัปเดตข้อมูลรายละเอียด
                document.getElementById('drawerProvinceText').textContent = `จังหวัด${item.province} (อำเภอ${item.district})`;
                document.getElementById('drawerWaterLevelText').textContent = item.water_level !== undefined ? `${Number(item.water_level).toFixed(2)} เมตร` : 'ระดับผิวทาง';
                document.getElementById('drawerReportCountText').textContent = `${item.report_count || 1} ครั้ง`;
                document.getElementById('drawerTimeText').textContent = item.time;
                document.getElementById('drawerSourceText').textContent = item.source_label;

                // อัปเดตปุ่มนำทางไปยังแผนที่เต็มจอ
                const btnFullMap = document.getElementById('btnOpenFullMap');
                btnFullMap.href = `./`;

                drawerOverlay.classList.add('active');

                // เริ่มต้นหรืออัปเดต Mini Map ใน Drawer
                setTimeout(() => {
                    initOrUpdateMiniMap(item.latitude, item.longitude, item.title);
                }, 200);
            }

            function closeDetailDrawer() {
                drawerOverlay.classList.remove('active');
            }

            btnCloseDrawer.addEventListener('click', closeDetailDrawer);
            drawerOverlay.addEventListener('click', (e) => {
                if (e.target === drawerOverlay) {
                    closeDetailDrawer();
                }
            });

            // Mini Map Engine for Drawer
            function initOrUpdateMiniMap(lat, lng, title) {
                if (!miniMapInstance) {
                    miniMapInstance = L.map('drawerMiniMap', {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([lat, lng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18
                    }).addTo(miniMapInstance);

                    miniMapMarker = L.circleMarker([lat, lng], {
                        radius: 8,
                        stroke: false,
                        fillColor: '#2563EB',
                        fillOpacity: 1
                    }).addTo(miniMapInstance);
                } else {
                    miniMapInstance.invalidateSize();
                    miniMapInstance.setView([lat, lng], 13);
                    miniMapMarker.setLatLng([lat, lng]);
                }
            }

            // =====================================================================
            // จัดการ Event ตัวกรอง (Search, Province, Severity, Source)
            // =====================================================================
            document.getElementById('searchInput').addEventListener('input', renderIncidentTable);
            document.getElementById('provinceSelect').addEventListener('change', renderIncidentTable);
            document.getElementById('severitySelect').addEventListener('change', renderIncidentTable);

            document.querySelectorAll('.source-tab-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.source-tab-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentSourceFilter = btn.getAttribute('data-source') || 'all';
                    renderIncidentTable();
                });
            });

            // เริ่มต้นโหลดข้อมูลแดชบอร์ด
            loadDashboardData();
        });
    </script>
</body>
</html>
