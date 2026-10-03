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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

    <style>
        /* ==========================================================================
           ROiCORE Design System Tokens (White Modern UI)
           อ้างอิงตามข้อกำหนดใน rules.md และ design.md
           ========================================================================== */
        :root {
            /* Brand Accent */
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;

            /* Neutral Surfaces (Surface Layering Architecture) */
            --bg-body: #F1F5F9;
            --surface: #FFFFFF;
            --surface-subtle: #F8FAFC;
            --surface-active: #E2E8F0;
            --surface-hover: #CBD5E1;

            /* Typography Colors */
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-light: #94A3B8;

            /* Semantic Status & Risk Colors */
            --risk-critical: #DC2626;
            --risk-critical-bg: #FEF2F2;
            --risk-high: #EA580C;
            --risk-high-bg: #FFF7ED;
            --risk-medium: #D97706;
            --risk-medium-bg: #FEF3C7;
            --risk-low: #2563EB;
            --risk-low-bg: #EFF6FF;
            --gistda-purple: #7C3AED;
            --gistda-tint: #EDE9FE;

            /* Radius Hierarchy Scale */
            --r-sm: 8px;
            --r-md: 10px;
            --r-lg: 14px;
            --r-xl: 20px;
            --r-full: 9999px;
        }

        /* Global Reset: ตัด Border และ Box-Shadow ทั้งหมดตามกฎ White Modern */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        body {
            font-family: 'Noto Sans Thai', 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* App Wrapper */
        .app-layout {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }

        /* Fullscreen Map Canvas */
        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
            background-color: #E2E8F0;
        }

        /* Top Floating Navbar */
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
        }

        .nav-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 10px 18px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            pointer-events: auto;
        }

        .brand-logo {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: var(--text-main);
            letter-spacing: -0.03em;
            text-decoration: none;
        }

        .brand-logo .accent-i {
            color: var(--accent-blue);
            font-style: normal;
        }

        .nav-tabs {
            display: flex;
            gap: 6px;
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

        /* Floating Action Button (FAB) */
        .fab-report {
            position: absolute;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            background-color: var(--accent-blue);
            color: #FFFFFF;
            padding: 14px 22px;
            border-radius: var(--r-full);
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s ease;
            text-decoration: none;
        }

        .fab-report:hover {
            background-color: var(--accent-blue-hover);
        }

        /* Modal Container Placeholder */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.4);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 24px;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
    </style>
</head>
<body>

    <div class="app-layout">
        <!-- Top Floating Navigation -->
        <header class="top-navbar">
            <div class="nav-card">
                <a href="/roicore/public/" class="brand-logo">
                    RO<span class="accent-i">i</span>CORE
                </a>
            </div>

            <nav class="nav-card">
                <div class="nav-tabs">
                    <a href="/roicore/public/" class="nav-tab active">
                        <i class="fa-solid fa-map-location-dot"></i> แผนที่
                    </a>
                    <a href="/roicore/public/?page=dashboard" class="nav-tab">
                        <i class="fa-solid fa-chart-pie"></i> แดชบอร์ด
                    </a>
                    <a href="/roicore/public/?page=api" class="nav-tab">
                        <i class="fa-solid fa-code"></i> สำหรับนักพัฒนา
                    </a>
                </div>
            </nav>
        </header>

        <!-- Main OpenStreetMap Container -->
        <main id="map"></main>

        <!-- Floating Action Button for Quick Incident Report -->
        <button type="button" class="fab-report" id="btnOpenReportModal">
            <i class="fa-solid fa-bullhorn"></i> แจ้งเหตุน้ำท่วมด่วน
        </button>
    </div>

    <!-- Quick Incident Report Modal Skeleton (≤ 3 Taps Flow) -->
    <div class="modal-overlay" id="reportModal">
        <div class="modal-card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h2 style="font-size:16px; font-weight:700;">
                    <i class="fa-solid fa-bullhorn" style="color:var(--accent-blue);"></i> รายงานสถานการณ์น้ำท่วม
                </h2>
                <button type="button" id="btnCloseReportModal" style="background:none; color:var(--text-muted); cursor:pointer; font-size:16px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- 
                TODO: Front-End Team
                เว้นพื้นที่สำหรับทีม Front-End นำโค้ดฟอร์มรายงานด่วนมาพัฒนาต่อตาม design.md และ rules.md:
                1. ตัวเลือกระดับน้ำ 4 ระดับ (เล็กน้อย / ปานกลาง / รถเล็กผ่านไม่ได้ / วิกฤตจมมิด)
                2. เบอร์โทรศัพท์ติดต่อ (บันทึกลง LocalStorage อัตโนมัติ)
                3. รูปถ่ายสถานที่จริง
                4. Geolocation ดึงพิกัดอัตโนมัติ
            -->
            <div style="background-color:var(--surface-subtle); border-radius:var(--r-lg); padding:20px; text-align:center; color:var(--text-muted); font-size:13px;">
                <i class="fa-solid fa-code" style="font-size:24px; margin-bottom:8px; display:block; color:var(--accent-blue);"></i>
                <strong>[TODO: Front-End Team]</strong><br>
                พัฒนาระบบ Quick Report Modal ($\le 3$ Taps) ตามข้อกำหนดใน <code>design.md</code>
            </div>
        </div>
    </div>

    <!-- Leaflet JS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

    <script>
        /**
         * =========================================================================
         * ROiCORE Map Controller Starter Skeleton
         * =========================================================================
         * ข้อแนะนำสำหรับทีม Front-End:
         * 1. ใช้ Leaflet 1.9.4 ในการสร้างแผนที่ OpenStreetMap
         * 2. ดึงข้อมูลจุดน้ำท่วมจาก GET /api/points
         * 3. ดึงข้อมูลภาพถ่ายดาวเทียมจาก GET /api/gistda (ครอบคลุม 77 จังหวัด)
         * 4. ดึงข้อมูลอาณาเขต Polygon จาก GET /api/zones
         * 5. ส่งรายงานเหตุการณ์ผ่าน POST /api/reports
         */

        document.addEventListener('DOMContentLoaded', () => {
            // เริ่มต้นแผนที่ OpenStreetMap (พิกัดศูนย์กลาง: ประเทศไทย / ร้อยเอ็ด)
            const map = L.map('map', {
                zoomControl: false
            }).setView([16.0538, 103.6520], 12);

            // เพิ่มชั้นข้อมูล OSM Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // ย้ายปุ่ม Zoom Control ไปมุมล่างซ้าย
            L.control.zoom({ position: 'bottomleft' }).addTo(map);

            // จัดการเปิด/ปิด Modal
            const reportModal = document.getElementById('reportModal');
            const btnOpen = document.getElementById('btnOpenReportModal');
            const btnClose = document.getElementById('btnCloseReportModal');

            btnOpen?.addEventListener('click', () => {
                reportModal.style.display = 'flex';
            });

            btnClose?.addEventListener('click', () => {
                reportModal.style.display = 'none';
            });

            // TODO: Front-End Team — เพิ่ม Logic การดึง API และวาด Marker/Polygon ที่นี่
            console.log('ROiCORE Map View initialized. Ready for Front-End implementation.');
        });
    </script>
</body>
</html>
