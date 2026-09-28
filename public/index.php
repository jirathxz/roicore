<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use RoiCore\Core\AppContainer;
use RoiCore\Presentation\Http\Request;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

$container = AppContainer::getInstance();
$request = Request::fromGlobals();

// Handle API requests
$response = $container->apiRouter->dispatch($request);
if ($response !== null) {
    $response->send();
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — หน้าทดสอบระบบการเชื่อมต่อข้อมูล</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;
            --bg-body: #F1F5F9;
            --surface: #FFFFFF;
            --surface-subtle: #F8FAFC;
            --surface-active: #E2E8F0;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --r-sm: 8px;
            --r-md: 12px;
            --r-lg: 16px;
            --r-xl: 24px;
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
            padding: 32px 16px;
            line-height: 1.6;
        }

        .container {
            max-width: 980px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .header {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .brand {
            font-family: 'Inter', sans-serif;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-main);
        }

        .brand span.accent {
            color: var(--accent-blue);
            font-style: normal;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
        }

        .desc {
            font-size: 14.5px;
            color: var(--text-muted);
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
        }

        .test-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .test-box-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .test-box-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-input, .form-select, .form-textarea {
            background-color: var(--surface);
            color: var(--text-main);
            font-family: inherit;
            font-size: 14px;
            padding: 10px 14px;
            border-radius: var(--r-md);
            width: 100%;
        }

        .form-textarea {
            resize: vertical;
            min-height: 70px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-action {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: var(--r-md);
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            width: 100%;
        }

        .btn-action:hover {
            background-color: var(--accent-blue-hover);
        }

        .btn-secondary {
            background-color: #E2E8F0;
            color: var(--text-main);
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 18px;
            border-radius: var(--r-md);
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            width: 100%;
        }

        .btn-secondary:hover {
            background-color: #CBD5E1;
        }

        .response-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .response-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .response-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
        }

        .response-status {
            font-size: 13.5px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .response-viewer {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 20px;
            font-family: 'Consolas', monospace;
            font-size: 13px;
            color: #1E293B;
            line-height: 1.6;
            max-height: 380px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <main class="container">
        <!-- Header -->
        <header class="card">
            <div class="header">
                <div class="brand">RO<span class="accent">i</span>CORE</div>
                <h1 class="title">หน้าทดสอบระบบการเชื่อมต่อข้อมูล</h1>
                <p class="desc">
                    ทดสอบการทำงานของระบบประเมินสถานการณ์น้ำท่วมและรายงานข้อมูลแบบเรียลไทม์
                </p>
            </div>
        </header>

        <!-- Test Panels -->
        <div class="grid-layout">
            <!-- Test 1: Submit Report -->
            <section class="card">
                <div class="test-box">
                    <h2 class="test-box-title">ส่งรายงานสถานการณ์น้ำท่วม</h2>
                    <p class="test-box-desc">บันทึกรายงานน้ำท่วมจากประชาชนพร้อมระบุระดับความรุนแรงและตำแหน่ง</p>
                    
                    <form id="reportForm" onsubmit="submitReport(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="reportLat">ละติจูด</label>
                                <input class="form-input" type="text" id="reportLat" value="16.0538">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="reportLng">ลองจิจูด</label>
                                <input class="form-input" type="text" id="reportLng" value="103.6520">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" for="reportSeverity">ระดับความรุนแรง</label>
                            <select class="form-select" id="reportSeverity">
                                <option value="1">ระดับต่ำ</option>
                                <option value="2">ระดับปานกลาง</option>
                                <option value="3" selected>ระดับวิกฤต</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" for="reportPhone">เบอร์โทรศัพท์ติดต่อ</label>
                            <input class="form-input" type="text" id="reportPhone" value="0812345678">
                        </div>

                        <div class="form-group" style="margin-top: 10px;">
                            <label class="form-label" for="reportDesc">รายละเอียดสถานการณ์</label>
                            <textarea class="form-textarea" id="reportDesc">น้ำเอ่อล้นตลิ่งแม่น้ำชี เอ่อท่วมถนนสายหลักระดับ 40 เซนติเมตร</textarea>
                        </div>

                        <button type="submit" class="btn-action" style="margin-top: 14px;">ส่งข้อมูลรายงานสถานการณ์</button>
                    </form>
                </div>
            </section>

            <!-- Test 2: Check Flood Points & Weather -->
            <section class="card">
                <div class="test-box">
                    <h2 class="test-box-title">ตรวจสอบจุดน้ำท่วมและความเสี่ยง</h2>
                    <p class="test-box-desc">ดึงข้อมูลจุดน้ำท่วมที่เฝ้าระวัง พร้อมคำนวณระดับความเสี่ยงตามปริมาณน้ำและฝน</p>
                    
                    <button type="button" class="btn-action" onclick="fetchFloodPoints()">ดึงข้อมูลจุดเสี่ยงน้ำท่วม</button>
                </div>

                <div class="test-box">
                    <h2 class="test-box-title">ตรวจสอบสภาพอากาศและฝน</h2>
                    <p class="test-box-desc">ตรวจสอบอุณหภูมิ ความชื้น ปริมาณฝนสะสม และทิศทางลมในพื้นที่</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="weatherLat">ละติจูด</label>
                            <input class="form-input" type="text" id="weatherLat" value="16.0538">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="weatherLng">ลองจิจูด</label>
                            <input class="form-input" type="text" id="weatherLng" value="103.6520">
                        </div>
                    </div>

                    <button type="button" class="btn-action" onclick="fetchWeather()" style="margin-top: 14px;">ตรวจสอบสภาพอากาศ</button>
                </div>

                <div class="test-box">
                    <h2 class="test-box-title">รายการรายงานทั้งหมดในระบบ</h2>
                    <p class="test-box-desc">ดึงรายการรายงานที่ประชาชนแจ้งเข้ามาทั้งหมดเพื่อตรวจสอบ</p>
                    
                    <button type="button" class="btn-secondary" onclick="fetchReports()">ดึงรายการรายงานทั้งหมด</button>
                </div>
            </section>
        </div>

        <!-- Live Response Panel -->
        <section class="card response-container">
            <div class="response-header">
                <h2 class="response-title">ผลลัพธ์ข้อมูล</h2>
                <span class="response-status" id="responseStatus">พร้อมรับคำขอทดสอบ</span>
            </div>
            <pre class="response-viewer" id="responseViewer">กดปุ่มทดสอบด้านบนเพื่อดูข้อมูลตอบกลับ</pre>
        </section>
    </main>

    <script>
        const statusEl = document.getElementById('responseStatus');
        const viewerEl = document.getElementById('responseViewer');

        function setStatus(text) {
            statusEl.textContent = text;
        }

        function displayData(data) {
            viewerEl.textContent = JSON.stringify(data, null, 2);
        }

        async function submitReport(event) {
            event.preventDefault();
            setStatus('กำลังส่งข้อมูล...');
            try {
                const payload = {
                    latitude: parseFloat(document.getElementById('reportLat').value),
                    longitude: parseFloat(document.getElementById('reportLng').value),
                    severity: parseInt(document.getElementById('reportSeverity').value, 10),
                    description: document.getElementById('reportDesc').value,
                    phone: document.getElementById('reportPhone').value
                };

                const res = await fetch('?api=reports', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                setStatus('ส่งรายงานสถานการณ์สำเร็จ');
                displayData(data);
            } catch (err) {
                setStatus('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                displayData({ error: err.message });
            }
        }

        async function fetchFloodPoints() {
            setStatus('กำลังดึงข้อมูลจุดน้ำท่วม...');
            try {
                const res = await fetch('?api=points');
                const data = await res.json();
                setStatus('ดึงข้อมูลจุดน้ำท่วมเรียบร้อยแล้ว');
                displayData(data);
            } catch (err) {
                setStatus('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                displayData({ error: err.message });
            }
        }

        async function fetchWeather() {
            setStatus('กำลังดึงข้อมูลสภาพอากาศ...');
            try {
                const lat = document.getElementById('weatherLat').value;
                const lng = document.getElementById('weatherLng').value;
                const res = await fetch(`?api=weather&lat=${lat}&lng=${lng}`);
                const data = await res.json();
                setStatus('ดึงข้อมูลสภาพอากาศเรียบร้อยแล้ว');
                displayData(data);
            } catch (err) {
                setStatus('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                displayData({ error: err.message });
            }
        }

        async function fetchReports() {
            setStatus('กำลังดึงรายการรายงาน...');
            try {
                const res = await fetch('?api=reports');
                const data = await res.json();
                setStatus('ดึงรายการรายงานเรียบร้อยแล้ว');
                displayData(data);
            } catch (err) {
                setStatus('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                displayData({ error: err.message });
            }
        }
    </script>
</body>
</html>
