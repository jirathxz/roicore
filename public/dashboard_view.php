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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* ==========================================================================
           ROiCORE Design System Tokens (White Modern UI)
           ========================================================================== */
        :root {
            /* Brand Accent */
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;

            /* Neutral Surfaces (Surface Layering) */
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
            --status-success: #16A34A;
            --status-success-bg: #F0FDF4;
            --gistda-purple: #7C3AED;
            --gistda-tint: #EDE9FE;

            /* Radius Hierarchy Scale */
            --r-sm: 8px;
            --r-md: 10px;
            --r-lg: 14px;
            --r-xl: 20px;
            --r-full: 9999px;
        }

        /* Global Reset: ตัด Border และ Shadow ทั้งหมด */
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
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 48px;
        }

        .dashboard-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
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
            padding: 10px 18px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
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

        /* Main Surface Card Skeleton */
        .card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .todo-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 24px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13.5px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <!-- Header & Top Navigation -->
        <header class="top-navbar">
            <div class="nav-card">
                <a href="/roicore/public/" class="brand-logo">
                    RO<span class="accent-i">i</span>CORE
                </a>
            </div>

            <nav class="nav-card">
                <div class="nav-tabs">
                    <a href="/roicore/public/" class="nav-tab">
                        <i class="fa-solid fa-map-location-dot"></i> แผนที่
                    </a>
                    <a href="/roicore/public/?page=dashboard" class="nav-tab active">
                        <i class="fa-solid fa-chart-pie"></i> แดชบอร์ด
                    </a>
                    <a href="/roicore/public/?page=api" class="nav-tab">
                        <i class="fa-solid fa-code"></i> สำหรับนักพัฒนา
                    </a>
                </div>
            </nav>
        </header>

        <!-- KPI Summary Cards Skeleton Section -->
        <section class="card">
            <h2 style="font-size:15px; font-weight:700;">
                <i class="fa-solid fa-chart-line" style="color:var(--accent-blue);"></i> สรุปภาพรวมสถานการณ์น้ำท่วม
            </h2>
            
            <!-- 
                TODO: Front-End Team
                เว้นพื้นที่สำหรับทีม Front-End สร้าง 4 KPI Stat Cards:
                1. จำนวนจังหวัดที่ประสบภัย (GET /api/stats)
                2. จุดวิกฤตสะสม
                3. รายงานวันนี้จากประชาชน (GET /api/reports)
                4. ดัชนีความเสี่ยงฝนตกสะสม (GET /api/weather)
            -->
            <div class="todo-box">
                <i class="fa-solid fa-table-cells-large" style="font-size:28px; margin-bottom:8px; display:block; color:var(--accent-blue);"></i>
                <strong>[TODO: Front-End Team] KPI Summary Cards</strong><br>
                สร้างกล่องสถิติภาพรวม 4 รายการ ตามข้อกำหนดใน <code>design.md</code>
            </div>
        </section>

        <!-- Incident Reports Table & Filter Section Skeleton -->
        <section class="card">
            <h2 style="font-size:15px; font-weight:700;">
                <i class="fa-solid fa-list-check" style="color:var(--accent-blue);"></i> ตรวจสอบรายละเอียดจุดรายงานทั่วประเทศ
            </h2>

            <!-- 
                TODO: Front-End Team
                เว้นพื้นที่สำหรับทีม Front-End สร้าง Toolbar ตัวกรอง:
                - Dropdown ค้นหารายจังหวัด (77 จังหวัด)
                - Filter ระดับความรุนแรง
                - Toggle ข้อมูลดาวเทียม GISTDA / ประชาชน
                - ตารางและ Drawer แสดงรูปภาพสถานที่จริง
            -->
            <div class="todo-box">
                <i class="fa-solid fa-filter" style="font-size:28px; margin-bottom:8px; display:block; color:var(--accent-blue);"></i>
                <strong>[TODO: Front-End Team] Filter Toolbar & Incident Report Table</strong><br>
                สร้างตารางค้นหาและตรวจสอบรายละเอียดจุดรายงานทั่วประเทศ พร้อม Detail Drawer
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('ROiCORE Dashboard View initialized. Ready for Front-End implementation.');
        });
    </script>
</body>
</html>
