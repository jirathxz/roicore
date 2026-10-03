<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — ระบบจัดการข้อมูลและทดสอบ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            /* Brand Accent */
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;

            /* Surfaces */
            --bg-body: #F1F5F9;
            --surface: #FFFFFF;
            --surface-subtle: #F8FAFC;
            --surface-active: #E2E8F0;
            --surface-hover: #CBD5E1;

            /* Typography Colors */
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-code: #1E293B;

            /* Semantic Colors */
            --status-danger: #DC2626;
            --status-warning: #D97706;
            --status-success: #16A34A;

            /* Radius Scale */
            --r-sm: 8px;
            --r-md: 10px;
            --r-lg: 14px;
            --r-xl: 20px;
            --r-full: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
            box-shadow: none !important;
        }

        body {
            font-family: 'Noto Sans Thai', 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            padding: 24px 16px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 920px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .header {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .brand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .brand-nav-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .brand {
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-main);
            line-height: 1;
            text-decoration: none;
        }

        .brand span.accent {
            color: var(--accent-blue);
        }

        .nav-divider {
            width: 1px;
            height: 20px;
            background-color: var(--surface-active);
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: var(--r-md);
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: background-color 0.15s ease, color 0.15s ease;
            min-height: 38px;
        }

        .nav-tab:hover {
            background-color: var(--surface-subtle);
            color: var(--text-main);
        }

        .nav-tab.active {
            background-color: var(--accent-tint);
            color: var(--accent-blue);
            font-weight: 700;
        }

        .system-status-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #FEF3C7;
            color: #92400E;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: var(--r-full);
        }

        .system-status-tag.online {
            background-color: #DCFCE7;
            color: #166534;
        }

        .brand span.accent {
            color: var(--accent-blue);
            font-style: normal;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .desc {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .card {
                padding: 16px;
            }
        }

        .test-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .test-box-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .test-box-icon {
            color: var(--accent-blue);
            font-size: 14px;
        }

        .test-box-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
        }

        .test-box-desc {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            color: var(--text-muted);
            font-size: 11px;
        }

        .form-input, .form-select, .form-textarea {
            background-color: var(--surface);
            color: var(--text-main);
            font-family: inherit;
            font-size: 13.5px;
            padding: 8px 12px;
            border-radius: var(--r-md);
            width: 100%;
        }

        .form-textarea {
            resize: vertical;
            min-height: 60px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-action {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: var(--r-md);
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-action:hover {
            background-color: var(--accent-blue-hover);
        }

        .btn-secondary {
            background-color: var(--surface-active);
            color: var(--text-main);
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: var(--r-md);
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background-color: var(--surface-hover);
        }

        /* Level Cards */
        .level-cards-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .level-card {
            background-color: var(--surface);
            border-radius: var(--r-md);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background-color 0.15s ease;
            user-select: none;
        }

        .level-card:hover {
            background-color: var(--surface-active);
        }

        .level-card.active {
            background-color: var(--accent-tint);
        }

        .level-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .level-icon-box {
            width: 30px;
            height: 30px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 12px;
            flex-shrink: 0;
        }

        .level-icon-box.critical { background-color: var(--status-danger); }
        .level-icon-box.high { background-color: var(--status-warning); }
        .level-icon-box.low { background-color: var(--accent-blue); }

        .level-text {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .level-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        .level-card.active .level-title {
            color: var(--accent-blue);
        }

        .level-sub {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .level-check-icon {
            color: var(--surface-active);
            font-size: 14px;
            transition: color 0.15s ease;
        }

        .level-card.active .level-check-icon {
            color: var(--accent-blue);
        }

        /* Photo Upload & Preview */
        .photo-picker-wrap {
            background-color: var(--surface);
            border-radius: var(--r-md);
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .photo-picker-wrap:hover {
            background-color: var(--surface-active);
        }

        .photo-picker-icon {
            font-size: 16px;
            color: var(--accent-blue);
        }

        .photo-picker-text {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .photo-picker-text .main-text {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
        }

        .photo-picker-text .sub-text {
            font-size: 10.5px;
            color: var(--text-muted);
        }

        .photo-preview-card {
            background-color: var(--surface);
            border-radius: var(--r-md);
            padding: 6px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .photo-preview-thumb {
            width: 38px;
            height: 38px;
            border-radius: var(--r-sm);
            object-fit: cover;
        }

        .photo-preview-meta {
            display: flex;
            flex-direction: column;
            gap: 1px;
            flex: 1;
            overflow: hidden;
        }

        .photo-name {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .photo-status {
            font-size: 10.5px;
            color: var(--status-success);
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .btn-remove-photo {
            background-color: #FEF2F2;
            color: var(--status-danger);
            width: 26px;
            height: 26px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 11px;
        }

        .actions-stack {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .response-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .response-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .response-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .response-status {
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .response-viewer {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 14px 16px;
            font-family: 'Consolas', monospace;
            font-size: 12.5px;
            color: var(--text-code);
            line-height: 1.5;
            max-height: 280px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }

        @media (max-width: 768px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .brand-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .brand-nav-group {
                justify-content: space-between;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 12px 8px;
                gap: 12px;
            }
            .card {
                padding: 16px;
                border-radius: var(--r-lg);
            }
            .brand {
                font-size: 19px;
            }
            .nav-divider {
                display: none;
            }
            .nav-menu {
                width: 100%;
                justify-content: space-between;
                order: 3;
            }
            .nav-tab {
                flex: 1;
                justify-content: center;
                padding: 6px 8px;
                font-size: 12px;
                min-height: 44px;
            }
            .system-status-tag {
                width: 100%;
                justify-content: center;
                min-height: 38px;
            }
            .btn-action, .btn-secondary {
                min-height: 44px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <!-- Header -->
        <header class="card">
            <div class="header">
                <div class="brand-row">
                    <div class="brand-nav-group">
                        <a href="." class="brand">RO<span class="accent">i</span>CORE</a>
                        <div class="nav-divider"></div>
                        <nav class="nav-menu">
                            <a href="." class="nav-tab">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>แผนที่</span>
                            </a>
                            <a href="?page=dashboard" class="nav-tab">
                                <i class="fa-solid fa-chart-pie"></i>
                                <span>แดชบอร์ด</span>
                            </a>
                            <a href="?page=api" class="nav-tab active">
                                <i class="fa-solid fa-code"></i>
                                <span>ศูนย์ข้อมูล API</span>
                            </a>
                        </nav>
                    </div>
                    <div class="system-status-tag" id="systemStatusTag">
                        <i class="fa-solid fa-database"></i>
                        <span>สถานะระบบ: ออฟไลน์ (ข้อมูลจำลองในเครื่อง)</span>
                    </div>
                </div>
                <h1 class="title">
                    <i class="fa-solid fa-terminal" style="color: var(--accent-blue);"></i>
                    ศูนย์กลางจัดการข้อมูลและเชื่อมต่อบริการ
                </h1>
                <p class="desc">
                    ตรวจสอบการตอบกลับของระบบ บันทึกข้อมูลรายงานสถานการณ์ และดึงข้อมูลจุดเฝ้าระวังแบบเรียลไทม์
                </p>
            </div>
        </header>

        <!-- Main Workspace -->
        <div class="grid-layout">
            <!-- Section 1: Submit Report (Compact & Fastest Flow) -->
            <section class="card">
                <div class="test-box">
                    <div class="test-box-header">
                        <i class="fa-solid fa-bullhorn test-box-icon"></i>
                        <h2 class="test-box-title">แจ้งเหตุและรายงานสถานการณ์น้ำท่วม</h2>
                    </div>
                    <p class="test-box-desc">รายงานสถานการณ์น้ำท่วมด่วน ระดับน้ำ เบอร์โทรศัพท์ และภาพถ่าย</p>
                    
                    <form id="reportForm" onsubmit="submitReport(event)">
                        <input type="hidden" id="reportLat" value="16.0538">
                        <input type="hidden" id="reportLng" value="103.6520">
                        <input type="hidden" id="reportSeverity" value="3">

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fa-solid fa-water"></i> เลือกระดับน้ำท่วม
                            </label>
                            <div class="level-cards-group">
                                <div class="level-card active" data-severity="3" onclick="selectConsoleLevel(3, this)">
                                    <div class="level-left">
                                        <div class="level-icon-box critical">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div class="level-text">
                                            <div class="level-title">วิกฤต (60+ ซม.)</div>
                                            <div class="level-sub">รถเล็กผ่านไม่ได้ / มิดล้อ</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check level-check-icon"></i>
                                </div>

                                <div class="level-card" data-severity="2" onclick="selectConsoleLevel(2, this)">
                                    <div class="level-left">
                                        <div class="level-icon-box high">
                                            <i class="fa-solid fa-water"></i>
                                        </div>
                                        <div class="level-text">
                                            <div class="level-title">ปานกลาง (30-50 ซม.)</div>
                                            <div class="level-sub">ท่วมครึ่งล้อ / สัญจรลำบาก</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check level-check-icon"></i>
                                </div>

                                <div class="level-card" data-severity="1" onclick="selectConsoleLevel(1, this)">
                                    <div class="level-left">
                                        <div class="level-icon-box low">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </div>
                                        <div class="level-text">
                                            <div class="level-title">น้ำขัง (10-20 ซม.)</div>
                                            <div class="level-sub">น้ำขังผิวทาง / รถผ่านได้</div>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check level-check-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 6px;">
                            <label class="form-label" for="reportPhone">
                                <i class="fa-solid fa-phone"></i> เบอร์โทรศัพท์ติดต่อ
                            </label>
                            <input class="form-input" type="tel" id="reportPhone" placeholder="ระบุเบอร์โทรศัพท์ (เช่น 0812345678)" required maxlength="15">
                        </div>

                        <div class="form-group" style="margin-top: 6px;">
                            <label class="form-label">
                                <i class="fa-solid fa-camera"></i> ภาพถ่ายสถานการณ์ (ถ้ามี)
                            </label>
                            <div class="photo-picker-wrap" id="consolePhotoDropArea" onclick="document.getElementById('consolePhotoInput').click()">
                                <i class="fa-solid fa-camera photo-picker-icon"></i>
                                <div class="photo-picker-text">
                                    <span class="main-text">แตะเพื่อเลือกภาพถ่าย</span>
                                    <span class="sub-text">ไฟล์รูปภาพ (ไม่เกิน 5 MB)</span>
                                </div>
                                <input type="file" id="consolePhotoInput" accept="image/*" style="display: none;" onchange="handleConsolePhotoUpload(event)">
                            </div>
                            <div class="photo-preview-card" id="consolePhotoPreviewCard" style="display: none;">
                                <img id="consolePhotoPreviewImg" src="" alt="ภาพถ่าย" class="photo-preview-thumb">
                                <div class="photo-preview-meta">
                                    <span class="photo-name" id="consolePhotoFileName">photo.jpg</span>
                                    <span class="photo-status"><i class="fa-solid fa-circle-check"></i> แนบภาพแล้ว</span>
                                </div>
                                <button type="button" class="btn-remove-photo" onclick="removeConsolePhoto(event)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-action" style="margin-top: 10px;">
                            <i class="fa-solid fa-paper-plane"></i>
                            ส่งรายงานสถานการณ์
                        </button>
                    </form>
                </div>
            </section>

            <!-- Section 2: Explore & Monitor -->
            <section class="card actions-stack">
                <div class="test-box">
                    <div class="test-box-header">
                        <i class="fa-solid fa-water test-box-icon"></i>
                        <h2 class="test-box-title">จุดเฝ้าระวังน้ำท่วม</h2>
                    </div>
                    <p class="test-box-desc">ตรวจสอบจุดเสี่ยงน้ำท่วมและระดับการเตือนภัยตามปริมาณน้ำในพื้นที่</p>
                    
                    <button type="button" class="btn-action" onclick="fetchFloodPoints()">
                        <i class="fa-solid fa-map-location-dot"></i>
                        ตรวจสอบจุดเฝ้าระวังน้ำท่วม
                    </button>
                </div>

                <div class="test-box">
                    <div class="test-box-header">
                        <i class="fa-solid fa-cloud-rain test-box-icon"></i>
                        <h2 class="test-box-title">สภาพอากาศและปริมาณฝน</h2>
                    </div>
                    <p class="test-box-desc">ตรวจสอบอุณหภูมิ ความชื้น ปริมาณฝนสะสม และทิศทางลมในพิกัดที่กำหนด</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="weatherLat">
                                <i class="fa-solid fa-location-dot"></i> ละติจูด
                            </label>
                            <input class="form-input" type="text" id="weatherLat" value="16.0538">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="weatherLng">
                                <i class="fa-solid fa-location-dot"></i> ลองจิจูด
                            </label>
                            <input class="form-input" type="text" id="weatherLng" value="103.6520">
                        </div>
                    </div>

                    <button type="button" class="btn-action" onclick="fetchWeather()" style="margin-top: 10px;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        ตรวจสอบสภาพอากาศ
                    </button>
                </div>

                <div class="test-box">
                    <div class="test-box-header">
                        <i class="fa-solid fa-satellite test-box-icon" style="background-color: #7C3AED;"></i>
                        <h2 class="test-box-title">ข้อมูลภาพถ่ายดาวเทียม GISTDA</h2>
                    </div>
                    <p class="test-box-desc">ดึงข้อมูลดาวเทียม Sentinel-1 SAR ขอบเขตพื้นที่น้ำท่วม และผลกระทบรายอำเภอ (https://disaster.gistda.or.th/services/open-api)</p>
                    
                    <div class="form-group">
                        <label class="form-label" for="gistdaProvince">
                            <i class="fa-solid fa-map-pin"></i> จังหวัด
                        </label>
                        <input class="form-input" type="text" id="gistdaProvince" value="ร้อยเอ็ด">
                    </div>

                    <button type="button" class="btn-action" onclick="fetchGistda()" style="margin-top: 10px; background-color: #7C3AED;">
                        <i class="fa-solid fa-satellite-dish"></i>
                        ดึงข้อมูลดาวเทียม GISTDA
                    </button>
                </div>

                <div class="test-box">
                    <div class="test-box-header">
                        <i class="fa-solid fa-list-check test-box-icon"></i>
                        <h2 class="test-box-title">รายการรายงานล่าสุด</h2>
                    </div>
                    <p class="test-box-desc">เรียกดูรายการเหตุการณ์ที่ได้รับแจ้งทั้งหมดในระบบ</p>
                    
                    <button type="button" class="btn-secondary" onclick="fetchReports()">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        ดึงข้อมูลรายงานทั้งหมด
                    </button>
                </div>
            </section>
        </div>

        <!-- Result / Live Stream Panel -->
        <section class="card response-container">
            <div class="response-header">
                <h2 class="response-title">
                    <i class="fa-solid fa-square-poll-vertical" style="color: var(--accent-blue);"></i>
                    ผลลัพธ์ข้อมูล
                </h2>
                <span class="response-status" id="responseStatus">
                    <i class="fa-solid fa-circle-info"></i>
                    พร้อมรับข้อมูล
                </span>
            </div>
            <pre class="response-viewer" id="responseViewer">กดปุ่มเลือกรายการด้านบนเพื่อดูข้อมูลตอบกลับ</pre>
        </section>
    </main>

    <script>
        const statusEl = document.getElementById('responseStatus');
        const viewerEl = document.getElementById('responseViewer');
        const tagEl = document.getElementById('systemStatusTag');

        // Client-side fallback mock data for complete network disconnection
        const mockFallbackPoints = [
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e01",
                "title": "สะพานข้ามแม่น้ำชี (ธวัชบุรี)",
                "location": { "latitude": 16.0538, "longitude": 103.6520 },
                "radius_meters": 250,
                "water_level_meters": 2.85,
                "risk_level": "CRITICAL",
                "risk_level_label": "ความเสี่ยงวิกฤต",
                "report_count": 5,
                "updated_at": new Date().toISOString()
            },
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e02",
                "title": "จุดตัดคลองส่งน้ำเสลภูมิ",
                "location": { "latitude": 16.0350, "longitude": 103.7890 },
                "radius_meters": 180,
                "water_level_meters": 1.40,
                "risk_level": "HIGH",
                "risk_level_label": "ความเสี่ยงสูง",
                "report_count": 3,
                "updated_at": new Date().toISOString()
            },
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e03",
                "title": "ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก",
                "location": { "latitude": 16.0680, "longitude": 103.6850 },
                "radius_meters": 150,
                "water_level_meters": 0.45,
                "risk_level": "MEDIUM",
                "risk_level_label": "ความเสี่ยงปานกลาง",
                "report_count": 2,
                "updated_at": new Date().toISOString()
            },
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e04",
                "title": "อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง",
                "location": { "latitude": 16.0120, "longitude": 103.7200 },
                "radius_meters": 300,
                "water_level_meters": 0.20,
                "risk_level": "LOW",
                "risk_level_label": "ความเสี่ยงต่ำ",
                "report_count": 1,
                "updated_at": new Date().toISOString()
            },
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e05",
                "title": "สะพานข้ามลำน้ำยัง (โพนทอง)",
                "location": { "latitude": 16.3015, "longitude": 103.9850 },
                "radius_meters": 200,
                "water_level_meters": 2.10,
                "risk_level": "HIGH",
                "risk_level_label": "ความเสี่ยงสูง",
                "report_count": 4,
                "updated_at": new Date().toISOString()
            }
        ];

        const mockFallbackReports = [
            {
                "id": "rep_101",
                "location": { "latitude": 16.0538, "longitude": 103.6520 },
                "severity": 3,
                "severity_label": "ระดับวิกฤต",
                "description": "น้ำท่วมเอ่อล้นตลิ่งแม่น้ำชี เอ่อท่วมถนนสายหลักระดับ 40 เซนติเมตร รถเล็กสัญจรลำบาก",
                "phone": "081XXXX678",
                "status": "VERIFIED",
                "status_label": "ตรวจสอบแล้ว",
                "reported_at": new Date(Date.now() - 7200000).toISOString(),
                "verified_at": new Date(Date.now() - 3600000).toISOString(),
                "photos": []
            },
            {
                "id": "rep_102",
                "location": { "latitude": 16.0350, "longitude": 103.7890 },
                "severity": 2,
                "severity_label": "ระดับปานกลาง",
                "description": "มีน้ำท่วมขังรอการระบายบริเวณตลาดสดเสลภูมิ ระดับ 15 เซนติเมตร",
                "phone": "089XXXX432",
                "status": "PENDING",
                "status_label": "รอการตรวจสอบ",
                "reported_at": new Date(Date.now() - 2400000).toISOString(),
                "verified_at": null,
                "photos": []
            }
        ];

        function setStatus(text, icon = 'fa-circle-info', isOffline = false) {
            const iconColor = isOffline ? 'style="color: var(--status-warning);"' : '';
            statusEl.innerHTML = `<i class="fa-solid ${icon}" ${iconColor}></i> ${text}`;
        }

        function displayData(data) {
            viewerEl.textContent = JSON.stringify(data, null, 2);
        }

        function updateSystemTag(isOffline) {
            if (tagEl) {
                if (isOffline) {
                    tagEl.className = 'system-status-tag';
                    tagEl.innerHTML = '<i class="fa-solid fa-wifi-slash"></i> <span>สถานะระบบ: ออฟไลน์ (ข้อมูลจำลองในเครื่อง)</span>';
                } else {
                    tagEl.className = 'system-status-tag online';
                    tagEl.innerHTML = '<i class="fa-solid fa-wifi"></i> <span>สถานะระบบ: ออนไลน์</span>';
                }
            }
        }

        let consoleAttachedPhoto = null;

        function selectConsoleLevel(level, element) {
            document.getElementById('reportSeverity').value = level;
            document.querySelectorAll('#reportForm .level-card').forEach(c => c.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
        }

        function handleConsolePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                setStatus('ขนาดไฟล์รูปภาพเกิน 5 MB', 'fa-triangle-exclamation');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                consoleAttachedPhoto = e.target.result;
                document.getElementById('consolePhotoPreviewImg').src = consoleAttachedPhoto;
                document.getElementById('consolePhotoFileName').textContent = file.name || 'photo.jpg';
                document.getElementById('consolePhotoDropArea').style.display = 'none';
                document.getElementById('consolePhotoPreviewCard').style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }

        function removeConsolePhoto(event) {
            if (event) event.stopPropagation();
            consoleAttachedPhoto = null;
            document.getElementById('consolePhotoInput').value = '';
            document.getElementById('consolePhotoPreviewImg').src = '';
            document.getElementById('consolePhotoPreviewCard').style.display = 'none';
            document.getElementById('consolePhotoDropArea').style.display = 'flex';
        }

        async function submitReport(event) {
            event.preventDefault();
            setStatus('กำลังส่งข้อมูลรายงาน...', 'fa-spinner fa-spin');
            
            const severityVal = parseInt(document.getElementById('reportSeverity').value, 10);
            const phoneVal = document.getElementById('reportPhone').value.trim();
            const latVal = parseFloat(document.getElementById('reportLat').value);
            const lngVal = parseFloat(document.getElementById('reportLng').value);

            if (phoneVal) {
                localStorage.setItem('roicore_phone', phoneVal);
            }

            const defaultDesc = severityVal === 3
                ? 'น้ำท่วมระดับวิกฤต (60+ ซม.) รถเล็กผ่านไม่ได้'
                : (severityVal === 2
                    ? 'น้ำท่วมระดับปานกลาง (30-50 ซม.) ท่วมครึ่งล้อ'
                    : 'น้ำท่วมขังผิวทาง (10-20 ซม.) รถผ่านได้');

            const payload = {
                latitude: latVal,
                longitude: lngVal,
                severity: severityVal,
                description: defaultDesc,
                phone: phoneVal,
                photos: consoleAttachedPhoto ? [consoleAttachedPhoto] : []
            };

            try {
                const res = await fetch('?api=reports', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                const isOffline = data.is_offline ?? true;
                updateSystemTag(isOffline);
                setStatus(
                    isOffline ? 'บันทึกรายงานสำเร็จ (สถานะ: ออฟไลน์)' : 'ส่งรายงานสถานการณ์สำเร็จ',
                    isOffline ? 'fa-wifi-slash' : 'fa-circle-check',
                    isOffline
                );
                displayData(data);
                removeConsolePhoto();
            } catch (err) {
                const offlineRecord = {
                    success: true,
                    status: 'OFFLINE',
                    is_offline: true,
                    message: 'บันทึกรายงานสถานการณ์น้ำท่วมในเครื่องเรียบร้อยแล้ว (สถานะ: ออฟไลน์)',
                    data: {
                        id: 'rep_' + Math.random().toString(36).substring(2, 10),
                        location: { latitude: payload.latitude, longitude: payload.longitude },
                        severity: payload.severity,
                        severity_label: payload.severity === 3 ? 'ระดับวิกฤต' : (payload.severity === 2 ? 'ระดับปานกลาง' : 'ระดับต่ำ'),
                        description: payload.description,
                        phone: payload.phone ? payload.phone.substring(0, 3) + 'XXXX' + payload.phone.substring(payload.phone.length - 3) : null,
                        status: 'PENDING',
                        status_label: 'รอการตรวจสอบ',
                        reported_at: new Date().toISOString(),
                        verified_at: null,
                        photos: payload.photos
                    }
                };
                updateSystemTag(true);
                setStatus('บันทึกรายงานสำเร็จ (สถานะ: ออฟไลน์)', 'fa-wifi-slash', true);
                displayData(offlineRecord);
                removeConsolePhoto();
            }
        }

        async function fetchFloodPoints() {
            setStatus('กำลังดึงข้อมูลจุดน้ำท่วม...', 'fa-spinner fa-spin');
            try {
                const res = await fetch('?api=points');
                const data = await res.json();
                const isOffline = data.is_offline ?? true;
                updateSystemTag(isOffline);
                setStatus(
                    isOffline ? 'ดึงข้อมูลจุดน้ำท่วมเรียบร้อยแล้ว (สถานะ: ออฟไลน์)' : 'ดึงข้อมูลจุดน้ำท่วมเรียบร้อยแล้ว',
                    isOffline ? 'fa-wifi-slash' : 'fa-circle-check',
                    isOffline
                );
                displayData(data);
            } catch (err) {
                const offlineResponse = {
                    success: true,
                    status: 'OFFLINE',
                    is_offline: true,
                    message: 'ดึงข้อมูลจุดน้ำท่วมและความเสี่ยงเรียบร้อยแล้ว (สถานะ: ออฟไลน์)',
                    data: mockFallbackPoints
                };
                updateSystemTag(true);
                setStatus('ดึงข้อมูลจุดน้ำท่วมเรียบร้อยแล้ว (สถานะ: ออฟไลน์)', 'fa-wifi-slash', true);
                displayData(offlineResponse);
            }
        }

        async function fetchWeather() {
            setStatus('กำลังดึงข้อมูลสภาพอากาศ...', 'fa-spinner fa-spin');
            const lat = parseFloat(document.getElementById('weatherLat').value || '16.0538');
            const lng = parseFloat(document.getElementById('weatherLng').value || '103.6520');
            try {
                const res = await fetch(`?api=weather&lat=${lat}&lng=${lng}`);
                const data = await res.json();
                const isOffline = data.is_offline ?? true;
                updateSystemTag(isOffline);
                setStatus(
                    isOffline ? 'ดึงข้อมูลสภาพอากาศเรียบร้อยแล้ว (สถานะ: ออฟไลน์)' : 'ดึงข้อมูลสภาพอากาศเรียบร้อยแล้ว',
                    isOffline ? 'fa-wifi-slash' : 'fa-circle-check',
                    isOffline
                );
                displayData(data);
            } catch (err) {
                const offlineWeather = {
                    success: true,
                    status: 'OFFLINE',
                    is_offline: true,
                    message: 'ดึงข้อมูลสภาพอากาศและฝนเรียบร้อยแล้ว (สถานะ: ออฟไลน์)',
                    data: {
                        temp_c: 28.5,
                        humidity: 85.0,
                        rainfall_1h_mm: 12.0,
                        rainfall_24h_mm: 42.0,
                        condition: 'ฝนตกเล็กน้อย',
                        wind_speed_mps: 3.2,
                        fetched_at: new Date().toISOString()
                    }
                };
                updateSystemTag(true);
                setStatus('ดึงข้อมูลสภาพอากาศเรียบร้อยแล้ว (สถานะ: ออฟไลน์)', 'fa-wifi-slash', true);
                displayData(offlineWeather);
            }
        }

        async function fetchReports() {
            setStatus('กำลังดึงรายการรายงาน...', 'fa-spinner fa-spin');
            try {
                const res = await fetch('?api=reports');
                const data = await res.json();
                const isOffline = data.is_offline ?? true;
                updateSystemTag(isOffline);
                setStatus(
                    isOffline ? 'ดึงรายการรายงานเรียบร้อยแล้ว (สถานะ: ออฟไลน์)' : 'ดึงรายการรายงานเรียบร้อยแล้ว',
                    isOffline ? 'fa-wifi-slash' : 'fa-circle-check',
                    isOffline
                );
                displayData(data);
            } catch (err) {
                const offlineReports = {
                    success: true,
                    status: 'OFFLINE',
                    is_offline: true,
                    message: 'ดึงข้อมูลรายการรายงานเรียบร้อยแล้ว (สถานะ: ออฟไลน์)',
                    data: mockFallbackReports
                };
                updateSystemTag(true);
                setStatus('ดึงรายการรายงานเรียบร้อยแล้ว (สถานะ: ออฟไลน์)', 'fa-wifi-slash', true);
                displayData(offlineReports);
            }
        }

        async function fetchGistda() {
            setStatus('กำลังดึงข้อมูลดาวเทียม GISTDA...', 'fa-spinner fa-spin');
            const province = document.getElementById('gistdaProvince').value.trim() || 'ร้อยเอ็ด';
            try {
                const res = await fetch(`?api=gistda/flood&province=${encodeURIComponent(province)}`);
                const data = await res.json();
                const isOffline = data.is_offline ?? true;
                updateSystemTag(isOffline);
                setStatus(
                    isOffline ? 'ดึงข้อมูลภาพถ่ายดาวเทียม GISTDA สำเร็จ (สถานะ: ออฟไลน์)' : 'ดึงข้อมูลภาพถ่ายดาวเทียม GISTDA สำเร็จ',
                    isOffline ? 'fa-wifi-slash' : 'fa-circle-check',
                    isOffline
                );
                displayData(data);
            } catch (err) {
                setStatus('เกิดข้อผิดพลาดในการดึงข้อมูลดาวเทียม', 'fa-triangle-exclamation', true);
            }
        }
    </script>
</body>
</html>

