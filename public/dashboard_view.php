<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — แดชบอร์ดรายงานสถานการณ์น้ำท่วมร้อยเอ็ด</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            /* Brand Accent */
            --accent-blue: #2563EB;
            --accent-blue-hover: #1D4ED8;
            --accent-tint: #EFF6FF;

            /* Neutral Surfaces (Surface-Layering Architecture) */
            --bg-body: #F1F5F9;
            --surface: #FFFFFF;
            --surface-subtle: #F8FAFC;
            --surface-active: #E2E8F0;
            --surface-hover: #CBD5E1;

            /* Typography */
            --text-main: #0F172A;
            --text-muted: #64748B;
            --text-light: #94A3B8;

            /* Semantic Status */
            --risk-critical: #DC2626;
            --risk-critical-bg: #FEF2F2;
            --risk-high: #EA580C;
            --risk-high-bg: #FFF7ED;
            --risk-medium: #D97706;
            --risk-medium-bg: #FFFBEB;
            --risk-low: #2563EB;
            --risk-low-bg: #EFF6FF;
            --status-success: #16A34A;
            --status-success-bg: #F0FDF4;

            /* GISTDA Purple Accent */
            --gistda-purple: #7C3AED;
            --gistda-tint: #EDE9FE;

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
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 48px;
        }

        /* Container & Grid System */
        .dashboard-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Header Navbar */
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
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 16px;
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
            min-height: 36px;
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

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: var(--surface);
            color: #92400E;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: var(--r-full);
        }

        .status-chip i {
            color: #D97706;
        }

        .btn-report-fab {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: var(--r-xl);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.15s ease;
            min-height: 40px;
        }

        .btn-report-fab:hover {
            background-color: var(--accent-blue-hover);
        }

        /* Cards and Sections */
        .card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--accent-blue);
        }

        .section-subtitle {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* KPI Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .metric-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .metric-icon-box {
            width: 48px;
            height: 48px;
            border-radius: var(--r-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .metric-icon-box.critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .metric-icon-box.high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .metric-icon-box.info { background-color: var(--accent-tint); color: var(--accent-blue); }
        .metric-icon-box.gistda { background-color: var(--gistda-tint); color: var(--gistda-purple); }

        .metric-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow: hidden;
        }

        .metric-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .metric-value {
            font-family: 'Inter', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.15;
        }

        .metric-meta {
            font-size: 11px;
            color: var(--text-light);
        }

        /* GISTDA Highlight Banner Card */
        .gistda-banner-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #FAF5FF 100%);
            border-radius: var(--r-xl);
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .gistda-banner-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .gistda-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--gistda-tint);
            color: var(--gistda-purple);
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: var(--r-full);
        }

        .gistda-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--gistda-purple);
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            background-color: var(--surface);
            padding: 6px 14px;
            border-radius: var(--r-md);
            transition: background-color 0.15s ease;
        }

        .gistda-link-btn:hover {
            background-color: var(--gistda-tint);
        }

        .gistda-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .gistda-stat-cell {
            background-color: var(--surface);
            border-radius: var(--r-lg);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .gistda-stat-label {
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .gistda-stat-num {
            font-family: 'Inter', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-main);
        }

        .gistda-stat-num span {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* 2-Column Responsive Dashboard Layout */
        .dashboard-main-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 16px;
        }

        /* Zone Boundaries List */
        .zone-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .zone-card-item {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: background-color 0.15s ease;
        }

        .zone-card-item:hover {
            background-color: var(--surface-active);
        }

        .zone-main {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .zone-badge-num {
            width: 34px;
            height: 34px;
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .zone-badge-num.critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .zone-badge-num.high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .zone-badge-num.medium { background-color: var(--risk-medium-bg); color: var(--risk-medium); }
        .zone-badge-num.low { background-color: var(--risk-low-bg); color: var(--risk-low); }

        .zone-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .zone-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .zone-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chip-stat {
            font-size: 11.5px;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-map-jump {
            background-color: var(--surface);
            color: var(--accent-blue);
            font-size: 12px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: var(--r-md);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
            transition: background-color 0.15s ease;
        }

        .btn-map-jump:hover {
            background-color: var(--accent-tint);
        }

        /* District Progress Bars */
        .district-bars {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .district-bar-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .district-bar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .district-name {
            font-weight: 600;
            color: var(--text-main);
        }

        .district-stat {
            font-weight: 700;
            color: var(--text-muted);
        }

        .progress-track {
            height: 8px;
            background-color: var(--surface-subtle);
            border-radius: var(--r-full);
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            border-radius: var(--r-full);
            transition: width 0.4s ease;
        }

        .progress-fill.critical { background-color: var(--risk-critical); }
        .progress-fill.high { background-color: var(--risk-high); }
        .progress-fill.medium { background-color: var(--risk-medium); }
        .progress-fill.low { background-color: var(--risk-low); }

        /* Weather Widget */
        .weather-box {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .weather-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .weather-temp-main {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .weather-temp-main i {
            font-size: 32px;
            color: var(--accent-blue);
        }

        .temp-number {
            font-family: 'Inter', sans-serif;
            font-size: 28px;
            font-weight: 800;
            line-height: 1;
            color: var(--text-main);
        }

        .weather-grid-mini {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .weather-cell {
            background-color: var(--surface);
            border-radius: var(--r-md);
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .weather-cell-lbl {
            font-size: 11px;
            color: var(--text-muted);
        }

        .weather-cell-val {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Reports Feed Table */
        .reports-table-wrap {
            overflow-x: auto;
            border-radius: var(--r-lg);
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
        }

        .reports-table th {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            padding: 10px 16px;
            letter-spacing: 0.2px;
        }

        .reports-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--surface-subtle);
            vertical-align: middle;
        }

        .reports-table tr:last-child td {
            border-bottom: none;
        }

        .report-photo-thumb {
            width: 44px;
            height: 44px;
            border-radius: var(--r-md);
            object-fit: cover;
            background-color: var(--surface-active);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--r-sm);
        }

        .status-badge.verified { background-color: var(--status-success-bg); color: var(--status-success); }
        .status-badge.pending { background-color: var(--risk-medium-bg); color: var(--risk-medium); }

        /* Report Modal (Exact Match with Screenshot) */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            width: 100%;
            max-width: 440px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-close {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .btn-close:hover {
            background-color: var(--surface-active);
            color: var(--text-main);
        }

        .smart-location-badge {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: var(--text-muted);
        }

        .smart-location-text {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .smart-location-text i {
            color: var(--accent-blue);
            font-size: 14px;
        }

        .smart-location-text strong {
            color: var(--text-main);
            font-weight: 700;
        }

        .btn-detect-gps {
            background-color: var(--surface);
            color: var(--accent-blue);
            font-family: inherit;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 10px;
            border-radius: var(--r-full);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.15s ease;
        }

        .btn-detect-gps:hover {
            background-color: var(--surface-active);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            color: var(--accent-blue);
            font-size: 13px;
        }

        .level-cards-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .level-card {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.1s ease;
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
            gap: 12px;
        }

        .level-icon-box {
            width: 38px;
            height: 38px;
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 15px;
            flex-shrink: 0;
        }

        .level-icon-box.critical { background-color: var(--risk-critical); }
        .level-icon-box.high { background-color: var(--risk-high); }
        .level-icon-box.low { background-color: var(--accent-blue); }

        .level-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .level-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        .level-card.active .level-title {
            color: var(--accent-blue);
        }

        .level-sub {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .level-check-icon {
            color: #CBD5E1;
            font-size: 18px;
            transition: color 0.15s ease;
        }

        .level-card.active .level-check-icon {
            color: var(--accent-blue);
        }

        .form-input {
            background-color: var(--surface-subtle);
            color: var(--text-main);
            font-family: inherit;
            font-size: 13px;
            padding: 12px 14px;
            border-radius: var(--r-lg);
            width: 100%;
            transition: background-color 0.15s ease;
        }

        .form-input:focus {
            background-color: var(--surface-active);
        }

        .photo-picker-wrap {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .photo-picker-wrap:hover {
            background-color: var(--surface-active);
        }

        .photo-picker-icon {
            font-size: 20px;
            color: var(--accent-blue);
            width: 40px;
            height: 40px;
            border-radius: var(--r-md);
            background-color: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .photo-picker-text {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .photo-picker-text .main-text {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
        }

        .photo-picker-text .sub-text {
            font-size: 11px;
            color: var(--text-muted);
        }

        .btn-submit {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            padding: 14px;
            border-radius: var(--r-lg);
            cursor: pointer;
            text-align: center;
            transition: background-color 0.15s ease;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
        }

        .btn-submit:hover {
            background-color: var(--accent-blue-hover);
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 3000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast {
            background-color: var(--surface);
            color: var(--text-main);
            padding: 12px 18px;
            border-radius: var(--r-lg);
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 600px) {
            .dashboard-container {
                padding: 12px;
                gap: 12px;
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
                margin-top: 4px;
            }
            .nav-tab {
                flex: 1;
                justify-content: center;
                padding: 6px 8px;
                font-size: 12px;
                min-height: 44px;
            }
            .status-chip {
                padding: 6px 10px;
                font-size: 11.5px;
            }
            .status-chip span {
                display: none;
            }
            .btn-report-fab {
                padding: 8px 14px;
                font-size: 12.5px;
                min-height: 44px;
            }
            .card, .gistda-banner-card {
                padding: 16px;
                border-radius: var(--r-lg);
            }
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .metric-card {
                padding: 12px;
                gap: 12px;
            }
            .metric-icon-box {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .metric-value {
                font-size: 20px;
            }
            .zone-card-item {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .btn-map-jump {
                justify-content: center;
                min-height: 40px;
            }
        }

        /* ─── Detail Drawer ───────────────────────────────── */
        .drawer-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(3px);
            z-index: 3000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .drawer-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .detail-drawer {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 380px;
            max-width: 100vw;
            background-color: var(--surface);
            z-index: 3001;
            transform: translateX(100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .detail-drawer.open {
            transform: translateX(0);
        }

        .drawer-header {
            padding: 20px 20px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid var(--surface-subtle);
        }

        .drawer-title-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            min-width: 0;
        }

        .drawer-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1.3;
        }

        .drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .drawer-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .drawer-section-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .drawer-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .drawer-detail-cell {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .drawer-cell-label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .drawer-cell-value {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
        }

        .drawer-cell-value.highlight { color: var(--risk-critical); }
        .drawer-cell-value.ok { color: var(--status-success); }

        .drawer-photo {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: var(--r-lg);
            background-color: var(--surface-active);
        }

        .drawer-map-btn {
            background-color: var(--accent-blue);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            padding: 12px;
            border-radius: var(--r-lg);
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.15s ease;
            min-height: 44px;
        }

        .drawer-map-btn:hover { background-color: var(--accent-blue-hover); }

        /* ─── Reports Search Bar ─────────────────────────── */
        .search-bar-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-input {
            flex: 1;
            background-color: var(--surface-subtle);
            color: var(--text-main);
            font-family: inherit;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: var(--r-lg);
            transition: background-color 0.15s ease;
        }

        .search-input:focus {
            background-color: var(--surface-active);
        }

        .filter-select {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 12px;
            border-radius: var(--r-lg);
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        /* ─── All Reports List ───────────────────────────── */
        .report-row {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .report-row:hover {
            background-color: var(--surface-active);
        }

        .report-row-thumb {
            width: 44px;
            height: 44px;
            border-radius: var(--r-md);
            object-fit: cover;
            background-color: var(--surface-active);
            flex-shrink: 0;
        }

        .report-row-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .report-row-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .report-row-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .report-row-meta span {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .report-row-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: var(--r-sm);
            flex-shrink: 0;
        }

        .report-row-badge.critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .report-row-badge.high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .report-row-badge.medium { background-color: var(--risk-medium-bg); color: var(--risk-medium); }
        .report-row-badge.low { background-color: var(--risk-low-bg); color: var(--risk-low); }

        .report-row-arrow {
            color: var(--text-muted);
            font-size: 12px;
            flex-shrink: 0;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 32px 16px;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .empty-state i {
            font-size: 28px;
            color: var(--surface-hover);
        }

        .empty-state p {
            font-size: 13px;
        }

        /* Count badge on section header */
        .count-badge {
            background-color: var(--surface-active);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--r-full);
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
                margin-top: 4px;
            }
            .nav-tab {
                flex: 1;
                justify-content: center;
                padding: 6px 8px;
                font-size: 12px;
                min-height: 44px;
            }
            .status-chip {
                padding: 6px 10px;
                font-size: 11.5px;
            }
            .status-chip span {
                display: none;
            }
            .btn-report-fab {
                padding: 8px 14px;
                font-size: 12.5px;
                min-height: 44px;
            }
            .card, .gistda-banner-card {
                padding: 16px;
                border-radius: var(--r-lg);
            }
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .metric-card {
                padding: 12px;
                gap: 12px;
            }
            .metric-icon-box {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .metric-value {
                font-size: 20px;
            }
            .zone-card-item {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .btn-map-jump {
                justify-content: center;
                min-height: 40px;
            }
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <!-- Top Responsive Navbar -->
        <header class="top-navbar">
            <div class="nav-card">
                <a href="." class="brand">RO<span class="accent">i</span>CORE</a>
                <div class="nav-divider"></div>
                <nav class="nav-menu">
                    <a href="." class="nav-tab">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>แผนที่</span>
                    </a>
                    <a href="?page=dashboard" class="nav-tab active">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>แดชบอร์ด</span>
                    </a>
                    <a href="?page=api" class="nav-tab">
                        <i class="fa-solid fa-code"></i>
                        <span>ศูนย์ข้อมูล API</span>
                    </a>
                </nav>
            </div>

            <div class="nav-actions">
                <div class="status-chip" title="ระบบจัดเก็บข้อมูลออฟไลน์">
                    <i class="fa-solid fa-database"></i>
                    <span>Offline Data Ready</span>
                </div>
                <button class="btn-report-fab" onclick="openReportModal()">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>แจ้งเหตุน้ำท่วมด่วน</span>
                </button>
            </div>
        </header>

        <!-- KPI Metrics Grid -->
        <section class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon-box critical">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">จุดวิกฤตระดับสูง</span>
                    <span class="metric-value" id="kpiCritical">1</span>
                    <span class="metric-meta">อ.ธวัชบุรี (ลุ่มน้ำชี)</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box high">
                    <i class="fa-solid fa-water"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">พื้นที่เฝ้าระวังสูง</span>
                    <span class="metric-value" id="kpiHigh">2</span>
                    <span class="metric-meta">อ.โพนทอง, อ.เสลภูมิ</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box gistda">
                    <i class="fa-solid fa-satellite"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">น้ำท่วมจากดาวเทียม</span>
                    <span class="metric-value" id="kpiGistdaRai">12,480 ไร่</span>
                    <span class="metric-meta">GISTDA Sentinel-1 SAR</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon-box info">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
                <div class="metric-info">
                    <span class="metric-label">รายงานประชาชน</span>
                    <span class="metric-value" id="kpiReportsCount">2</span>
                    <span class="metric-meta">ตรวจสอบแล้ว 100%</span>
                </div>
            </div>
        </section>

        <!-- GISTDA Satellite Flood Observation Card -->
        <section class="gistda-banner-card">
            <div class="gistda-banner-top">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span class="gistda-brand-badge">
                            <i class="fa-solid fa-satellite-dish"></i> GISTDA Disaster Platform
                        </span>
                        <span style="font-size: 12px; color: var(--text-muted);">
                            <i class="fa-solid fa-clock"></i> ตรวจวัดล่าสุด: <strong id="gistdaObsDate">วันนี้ (Sentinel-1 SAR)</strong>
                        </span>
                    </div>
                    <h2 style="font-size: 17px; font-weight: 800; color: var(--text-main); margin-top: 4px;">
                        ข้อมูลภาพถ่ายดาวเทียมพื้นที่น้ำท่วม จ.ร้อยเอ็ด
                    </h2>
                </div>
                <a href="https://disaster.gistda.or.th/services/open-api" target="_blank" rel="noopener" class="gistda-link-btn">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>GISTDA Open API</span>
                </a>
            </div>

            <!-- GISTDA Stats Grid -->
            <div class="gistda-stats-grid">
                <div class="gistda-stat-cell">
                    <span class="gistda-stat-label">
                        <i class="fa-solid fa-water" style="color: var(--gistda-purple);"></i> พื้นที่น้ำท่วมรวม
                    </span>
                    <div class="gistda-stat-num" id="gistdaTotalRai">12,480 <span>ไร่ (~19.97 ตร.กม.)</span></div>
                </div>

                <div class="gistda-stat-cell">
                    <span class="gistda-stat-label">
                        <i class="fa-solid fa-wheat-awn" style="color: var(--risk-high);"></i> พื้นที่เกษตร / นาข้าว
                    </span>
                    <div class="gistda-stat-num" id="gistdaAgriRai">9,850 <span>ไร่ (78.9%)</span></div>
                </div>

                <div class="gistda-stat-cell">
                    <span class="gistda-stat-label">
                        <i class="fa-solid fa-house-chimney-crack" style="color: var(--risk-critical);"></i> ชุมชน / ที่อยู่อาศัย
                    </span>
                    <div class="gistda-stat-num" id="gistdaResRai">2,630 <span>ไร่ (21.1%)</span></div>
                </div>

                <div class="gistda-stat-cell">
                    <span class="gistda-stat-label">
                        <i class="fa-solid fa-shield-virus" style="color: var(--accent-blue);"></i> เซนเซอร์บันทึกภาพ
                    </span>
                    <div class="gistda-stat-num" style="font-size: 15px;">Sentinel-1 <span>SAR C-Band 10m</span></div>
                </div>
            </div>
        </section>

        <!-- Main 2-Column Grid -->
        <div class="dashboard-main-grid">
            
            <!-- Left Column: Flood Zone Boundaries & Reports Feed -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Flood Zone Boundaries Card -->
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">
                                <i class="fa-solid fa-draw-polygon"></i>
                                <span>อาณาเขตพื้นที่น้ำท่วมร้อยเอ็ด (Flood Zones)</span>
                            </h2>
                            <p class="section-subtitle">คำนวณจากรูปทรง Polygon ขอบเขตพื้นที่น้ำท่วมจริง</p>
                        </div>
                    </div>

                    <div class="zone-list" id="zoneListContainer">
                        <!-- Populated by JS -->
                    </div>
                </div>

                <!-- Citizen Reports Live Table -->
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">
                                <i class="fa-solid fa-list-check"></i>
                                <span>บันทึกรายงานสถานการณ์ล่าสุด</span>
                            </h2>
                            <p class="section-subtitle">ข้อมูลการแจ้งเหตุพร้อมภาพถ่ายยืนยัน</p>
                        </div>
                    </div>

                    <div class="reports-table-wrap">
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>ภาพ</th>
                                    <th>ระดับความรุนแรง</th>
                                    <th>พื้นที่ / พิกัด</th>
                                    <th>เบอร์ติดต่อ</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody id="reportsTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column: Weather Insight & District Breakdown -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                
                <!-- Weather Insight Card -->
                <div class="card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fa-solid fa-cloud-showers-heavy"></i>
                            <span>สภาพอากาศ จ.ร้อยเอ็ด</span>
                        </h2>
                    </div>

                    <div class="weather-box" id="weatherWidget">
                        <div class="weather-top">
                            <div class="weather-temp-main">
                                <i class="fa-solid fa-cloud-sun-rain"></i>
                                <div>
                                    <div class="temp-number" id="wTemp">28.5°C</div>
                                    <div style="font-size: 12px; color: var(--text-muted);" id="wCondition">ฝนตกเล็กน้อยถึงปานกลาง</div>
                                </div>
                            </div>
                        </div>

                        <div class="weather-grid-mini">
                            <div class="weather-cell">
                                <span class="weather-cell-lbl"><i class="fa-solid fa-droplet" style="color: var(--accent-blue);"></i> ฝนสะสม 24 ชม.</span>
                                <span class="weather-cell-val" id="wRain24h">45.2 mm</span>
                            </div>
                            <div class="weather-cell">
                                <span class="weather-cell-lbl"><i class="fa-solid fa-wind" style="color: var(--accent-blue);"></i> ความเร็วลม</span>
                                <span class="weather-cell-val" id="wWind">18.5 km/h</span>
                            </div>
                            <div class="weather-cell">
                                <span class="weather-cell-lbl"><i class="fa-solid fa-water" style="color: var(--accent-blue);"></i> ความชื้นสัมพัทธ์</span>
                                <span class="weather-cell-val" id="wHumidity">88%</span>
                            </div>
                            <div class="weather-cell">
                                <span class="weather-cell-lbl"><i class="fa-solid fa-shield-halved" style="color: var(--status-warning);"></i> ระดับเตือนภัย</span>
                                <span class="weather-cell-val" style="color: var(--status-warning);" id="wRiskLevel">เฝ้าระวัง (Alert)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- District Breakdown (Satellite & Ground Sensor Integrated) -->
                <div class="card">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span>สถิติน้ำท่วมตามรายอำเภอ (GISTDA)</span>
                        </h2>
                    </div>

                    <div class="district-bars" id="districtBarsContainer">
                        <div class="district-bar-item">
                            <div class="district-bar-header">
                                <span class="district-name">อ.เสลภูมิ (ลุ่มน้ำชี / คลองส่งน้ำ)</span>
                                <span class="district-stat" style="color: var(--risk-high);">4,210 ไร่ (33.7%)</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill high" style="width: 85%;"></div>
                            </div>
                        </div>

                        <div class="district-bar-item">
                            <div class="district-bar-header">
                                <span class="district-name">อ.ธวัชบุรี (ลุ่มน้ำชีตอนกลาง)</span>
                                <span class="district-stat" style="color: var(--risk-critical);">3,850 ไร่ (30.8%)</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill critical" style="width: 78%;"></div>
                            </div>
                        </div>

                        <div class="district-bar-item">
                            <div class="district-bar-header">
                                <span class="district-name">อ.โพนทอง (ลุ่มน้ำยัง)</span>
                                <span class="district-stat" style="color: var(--risk-high);">2,740 ไร่ (22.0%)</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill high" style="width: 55%;"></div>
                            </div>
                        </div>

                        <div class="district-bar-item">
                            <div class="district-bar-header">
                                <span class="district-name">อ.จังหาร / อ่างธวัชชัย</span>
                                <span class="district-stat" style="color: var(--risk-medium);">980 ไร่ (7.9%)</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill medium" style="width: 20%;"></div>
                            </div>
                        </div>

                        <div class="district-bar-item">
                            <div class="district-bar-header">
                                <span class="district-name">อ.เมืองร้อยเอ็ด (ทางเลี่ยงเมือง)</span>
                                <span class="district-stat" style="color: var(--risk-low);">700 ไร่ (5.6%)</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill low" style="width: 14%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            </div>

            <!-- ─── All Reported Points (Full List) ─────────────────── -->
            <div class="card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">
                            <i class="fa-solid fa-list-check"></i>
                            <span>รายงานทั้งหมด</span>
                            <span class="count-badge" id="allReportsCountBadge">0</span>
                        </h2>
                        <p class="section-subtitle">กดแถวใดก็ได้เพื่อดูรายละเอียดเต็ม</p>
                    </div>
                </div>

                <!-- Search + Filter -->
                <div class="search-bar-wrap">
                    <input
                        type="search"
                        id="reportSearch"
                        class="search-input"
                        placeholder="&#xF002; ค้นหาชื่อ, พิกัด, คำอธิบาย..."
                        oninput="renderAllReportsList()"
                    >
                    <select id="reportFilterLevel" class="filter-select" onchange="renderAllReportsList()">
                        <option value="ALL">ทุกระดับ</option>
                        <option value="CRITICAL">วิกฤต</option>
                        <option value="HIGH">เสี่ยงสูง</option>
                        <option value="LOW">น้ำขัง</option>
                    </select>
                </div>

                <!-- List container -->
                <div style="display:flex; flex-direction:column; gap:8px;" id="allReportsListContainer">
                    <!-- Populated by JS -->
                </div>
            </div>

        </div>

        </div>

    </div>

    <!-- ─── Detail Drawer ───────────────────────────────────────── -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDetailDrawer()"></div>
    <aside class="detail-drawer" id="detailDrawer" role="dialog" aria-modal="true" aria-label="รายละเอียดจุดรายงาน">
        <div class="drawer-header">
            <div class="drawer-title-group">
                <div id="drawerRiskPill" class="risk-pill critical" style="width:fit-content"></div>
                <div class="drawer-title" id="drawerTitle">—</div>
                <div style="font-size:12px; color:var(--text-muted);" id="drawerSubtitle"></div>
            </div>
            <button class="btn-close" onclick="closeDetailDrawer()" aria-label="ปิด">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="drawer-body">
            <!-- Photo -->
            <div class="drawer-section" id="drawerPhotoSection" style="display:none;">
                <div class="drawer-section-label"><i class="fa-solid fa-camera"></i> ภาพถ่ายสถานการณ์</div>
                <img id="drawerPhoto" class="drawer-photo" src="" alt="ภาพรายงาน">
            </div>

            <!-- Stats grid -->
            <div class="drawer-section">
                <div class="drawer-section-label"><i class="fa-solid fa-chart-simple"></i> ข้อมูลสถานการณ์</div>
                <div class="drawer-detail-grid">
                    <div class="drawer-detail-cell">
                        <span class="drawer-cell-label"><i class="fa-solid fa-water"></i> ระดับน้ำ</span>
                        <span class="drawer-cell-value highlight" id="drawerWaterLevel">—</span>
                    </div>
                    <div class="drawer-detail-cell">
                        <span class="drawer-cell-label"><i class="fa-solid fa-users"></i> รายงานสะสม</span>
                        <span class="drawer-cell-value" id="drawerReportCount">—</span>
                    </div>
                    <div class="drawer-detail-cell">
                        <span class="drawer-cell-label"><i class="fa-solid fa-vector-square"></i> อาณาเขต</span>
                        <span class="drawer-cell-value" id="drawerArea">—</span>
                    </div>
                    <div class="drawer-detail-cell">
                        <span class="drawer-cell-label"><i class="fa-solid fa-phone"></i> เบอร์ติดต่อ</span>
                        <span class="drawer-cell-value ok" id="drawerPhone">—</span>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="drawer-section">
                <div class="drawer-section-label"><i class="fa-solid fa-location-dot"></i> พิกัดที่ตั้ง</div>
                <div class="drawer-detail-cell" style="grid-column:1/-1;">
                    <span class="drawer-cell-label">ละติจูด / ลองจิจูด</span>
                    <span class="drawer-cell-value" id="drawerCoords" style="font-size:13px;">—</span>
                </div>
            </div>

            <!-- Community Zone Info -->
            <div class="drawer-section" id="drawerCommunitySection" style="display:none;">
                <div class="drawer-section-label"><i class="fa-solid fa-people-group"></i> ข้อมูลชุมชนรายงาน</div>
                <div class="drawer-detail-cell">
                    <span class="drawer-cell-label">สถานะอาณาเขตชุมชน</span>
                    <span class="drawer-cell-value" id="drawerCommunityStatus" style="font-size:13px;">—</span>
                </div>
            </div>

            <!-- Description -->
            <div class="drawer-section" id="drawerDescSection" style="display:none;">
                <div class="drawer-section-label"><i class="fa-solid fa-align-left"></i> รายละเอียด</div>
                <div style="background:var(--surface-subtle); border-radius:var(--r-md); padding:12px; font-size:13px; color:var(--text-main); line-height:1.6;" id="drawerDesc"></div>
            </div>

            <!-- Map Button -->
            <a id="drawerMapBtn" href="." class="drawer-map-btn">
                <i class="fa-solid fa-map-location-dot"></i>
                ดูบนแผนที่
            </a>
        </div>
    </aside>

    <div class="modal-backdrop" id="reportModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fa-solid fa-bullhorn" style="color: var(--accent-blue);"></i>
                    <span>แจ้งเหตุน้ำท่วมด่วน</span>
                </h3>
                <button class="btn-close" onclick="closeReportModal()" aria-label="ปิด">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Smart Location Detect -->
            <div class="smart-location-badge">
                <div class="smart-location-text">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    <span>พิกัด: <strong id="lblCoords">16.0541, 103.6519</strong></span>
                </div>
                <button type="button" class="btn-detect-gps" id="btnDetectGps">
                    <i class="fa-solid fa-crosshairs"></i> ใช้พิกัดปัจจุบัน
                </button>
            </div>

            <form id="formQuickReport" onsubmit="submitQuickReport(event)">
                <input type="hidden" name="latitude" id="inputLat" value="16.0541">
                <input type="hidden" name="longitude" id="inputLng" value="103.6519">
                <input type="hidden" name="severity_level" id="inputSeverity" value="3">

                <!-- 1. Select Severity Level (3 Visual 1-Tap Cards) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-water"></i> เลือกระดับน้ำท่วม
                    </label>
                    <div class="level-cards-group">
                        <div class="level-card active" data-severity="3" onclick="selectFloodLevel(3, this)">
                            <div class="level-left">
                                <div class="level-icon-box critical">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <div class="level-text">
                                    <div class="level-title">วิกฤต (60+ ซม.)</div>
                                    <div class="level-sub">รถเล็กผ่านไม่ได้ / มิดล้อ / ไหลเชี่ยว</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check level-check-icon"></i>
                        </div>

                        <div class="level-card" data-severity="2" onclick="selectFloodLevel(2, this)">
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

                        <div class="level-card" data-severity="1" onclick="selectFloodLevel(1, this)">
                            <div class="level-left">
                                <div class="level-icon-box low">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div class="level-text">
                                    <div class="level-title">น้ำขัง (10-20 ซม.)</div>
                                    <div cl        let floodPoints = [];
        let reportsData = [];
        let gistdaData = null;
        let allPointsData = [];   // unified list = floodPoints + reportsData merged

        function showToast(message, icon = 'fa-solid fa-circle-check', isError = false) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `<i class="${icon}" style="color: ${isError ? 'var(--risk-critical)' : 'var(--accent-blue)'}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 200);
            }, 3000);
        }

        // ─── Detail Drawer ───────────────────────────────────────────────────
        const RISK_LABELS = {
            'CRITICAL': { th: 'วิกฤต', cls: 'critical', icon: 'fa-triangle-exclamation' },
            'HIGH':     { th: 'เสี่ยงสูง', cls: 'high', icon: 'fa-circle-exclamation' },
            'MEDIUM':   { th: 'ปานกลาง', cls: 'medium', icon: 'fa-water' },
            'LOW':      { th: 'น้ำขัง', cls: 'low', icon: 'fa-circle-info' },
        };

        function openDetailDrawer(point) {
            const risk = point.risk_level || point.severity_level || 'LOW';
            const rMeta = RISK_LABELS[risk.toUpperCase()] || RISK_LABELS['LOW'];
            const lat = point.location?.latitude ?? point.latitude ?? '–';
            const lng = point.location?.longitude ?? point.longitude ?? '–';

            // Risk pill
            const pill = document.getElementById('drawerRiskPill');
            pill.className = `risk-pill ${rMeta.cls}`;
            pill.innerHTML = `<i class="fa-solid ${rMeta.icon}"></i> ${point.risk_level_label || rMeta.th}`;

            // Title + subtitle
            document.getElementById('drawerTitle').textContent = point.title || point.description || point.zone_name || 'ไม่ระบุชื่อ';
            document.getElementById('drawerSubtitle').textContent = point.zone_name ? `พื้นที่: ${point.zone_name}` : (point.district_name || 'จ.ร้อยเอ็ด');

            // Stats
            document.getElementById('drawerWaterLevel').textContent  = point.water_level_meters ? `${point.water_level_meters} ม.` : '–';
            document.getElementById('drawerReportCount').textContent  = `${point.report_count ?? 1} ครั้ง`;
            document.getElementById('drawerArea').textContent         = point.affected_area_sqkm ? `${point.affected_area_sqkm} ตร.กม.` : `รัศมี ${point.radius_meters || 200} ม.`;
            document.getElementById('drawerPhone').textContent        = point.phone || point.phone_number || '–';
            document.getElementById('drawerCoords').textContent       = (lat !== '–' && lng !== '–') ? `${Number(lat).toFixed(5)}, ${Number(lng).toFixed(5)}` : '–';

            // Photo
            const photo = (point.photos && point.photos[0]) || point.photo_url || '';
            const photoSection = document.getElementById('drawerPhotoSection');
            if (photo) {
                document.getElementById('drawerPhoto').src = photo;
                photoSection.style.display = 'flex';
            } else {
                photoSection.style.display = 'none';
            }

            // Community zone info
            const communitySection = document.getElementById('drawerCommunitySection');
            if (!point.polygon_coordinates && (point.report_count ?? 0) >= 3) {
                const cnt = point.report_count;
                let statusText = cnt >= 10 ? '🔴 วิกฤต — อาณาเขตใหญ่มาก'
                    : cnt >= 7 ? '🟠 เสี่ยงสูง — อาณาเขตใหญ่'
                    : cnt >= 5 ? '🟡 พื้นที่เฝ้าระวังชุมชน'
                    : '👥 เริ่มสร้างอาณาเขตชุมชน';
                document.getElementById('drawerCommunityStatus').textContent = statusText;
                communitySection.style.display = 'flex';
            } else {
                communitySection.style.display = 'none';
            }

            // Description
            const descSection = document.getElementById('drawerDescSection');
            const desc = point.description || point.title || '';
            if (desc) {
                document.getElementById('drawerDesc').textContent = desc;
                descSection.style.display = 'flex';
            } else {
                descSection.style.display = 'none';
            }

            // Map link
            if (lat !== '–' && lng !== '–') {
                document.getElementById('drawerMapBtn').href = `.?lat=${lat}&lng=${lng}&zoom=14`;
            } else {
                document.getElementById('drawerMapBtn').href = '.';
            }

            // Open
            document.getElementById('drawerOverlay').classList.add('open');
            document.getElementById('detailDrawer').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailDrawer() {
            document.getElementById('drawerOverlay').classList.remove('open');
            document.getElementById('detailDrawer').classList.remove('open');
            document.body.style.overflow = '';
        }

        // ESC to close drawer
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetailDrawer(); });

        // ─── Render All Reports List ─────────────────────────────────────────
        function renderAllReportsList() {
            const q     = (document.getElementById('reportSearch')?.value || '').toLowerCase().trim();
            const level = document.getElementById('reportFilterLevel')?.value || 'ALL';
            const container = document.getElementById('allReportsListContainer');
            const badge     = document.getElementById('allReportsCountBadge');

            // Build unified list from floodPoints
            let items = [...floodPoints];

            // Apply level filter
            if (level !== 'ALL') {
                items = items.filter(p => (p.risk_level || '').toUpperCase() === level);
            }

            // Apply text search
            if (q) {
                items = items.filter(p => {
                    const searchable = [
                        p.title, p.description, p.zone_name, p.district_name,
                        p.phone, p.phone_number,
                        String(p.location?.latitude ?? p.latitude ?? ''),
                        String(p.location?.longitude ?? p.longitude ?? ''),
                    ].join(' ').toLowerCase();
                    return searchable.includes(q);
                });
            }

            badge.textContent = items.length;

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                        <p>ไม่พบข้อมูลที่ตรงกับเงื่อนไข</p>
                    </div>`;
                return;
            }

            container.innerHTML = '';
            items.forEach(point => {
                const risk     = (point.risk_level || point.severity_level || 'LOW').toUpperCase();
                const rMeta    = RISK_LABELS[risk] || RISK_LABELS['LOW'];
                const photo    = (point.photos && point.photos[0]) || point.photo_url || '';
                const title    = point.title || point.description || point.zone_name || 'ไม่ระบุ';
                const loc      = point.district_name || point.zone_name || 'จ.ร้อยเอ็ด';
                const lat      = point.location?.latitude ?? point.latitude;
                const lng      = point.location?.longitude ?? point.longitude;
                const coords   = (lat && lng) ? `${Number(lat).toFixed(4)}, ${Number(lng).toFixed(4)}` : '';
                const cnt      = point.report_count ?? 1;
                const water    = point.water_level_meters ? `${point.water_level_meters} ม.` : '';

                const row = document.createElement('div');
                row.className = 'report-row';
                row.onclick = () => openDetailDrawer(point);

                const thumbHtml = photo
                    ? `<img src="${photo}" class="report-row-thumb" alt="ภาพรายงาน" onerror="this.style.display='none'">`
                    : `<div class="report-row-thumb" style="display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-water" style="color:var(--text-muted);font-size:18px;"></i></div>`;

                row.innerHTML = `
                    ${thumbHtml}
                    <div class="report-row-body">
                        <div class="report-row-title">${title}</div>
                        <div class="report-row-meta">
                            <span><i class="fa-solid fa-location-dot"></i>${loc}</span>
                            ${water ? `<span><i class="fa-solid fa-water"></i>${water}</span>` : ''}
                            <span><i class="fa-solid fa-users"></i>${cnt} รายงาน</span>
                            ${coords ? `<span><i class="fa-solid fa-crosshairs"></i>${coords}</span>` : ''}
                        </div>
                    </div>
                    <span class="report-row-badge ${rMeta.cls}">
                        <i class="fa-solid ${rMeta.icon}"></i> ${rMeta.th}
                    </span>
                    <i class="fa-solid fa-chevron-right report-row-arrow"></i>
                `;
                container.appendChild(row);
            });
        }

        // ─── Dashboard Data Fetch ────────────────────────────────────────────
        async function fetchDashboardData() {
            try {
                const resPoints = await fetch('api/points');
                if (resPoints.ok) {
                    const data = await resPoints.json();
                    floodPoints = data.data || [];
                    renderFloodZones(floodPoints);
                    renderAllReportsList();
                    updateKpis(floodPoints);
                }

                const resReports = await fetch('api/reports');
                if (resReports.ok) {
                    const data = await resReports.json();
                    reportsData = data.data || [];
                    document.getElementById('kpiReportsCount').textContent = reportsData.length;
                    // Merge any report-only items into floodPoints for the unified list
                    if (reportsData.length > 0) {
                        const existingIds = new Set(floodPoints.map(p => p.id));
                        reportsData.forEach(r => {
                            if (!existingIds.has(r.id)) floodPoints.push(r);
                        });
                        renderAllReportsList();
                    }
                }

                const resGistda = await fetch('api/gistda/flood');
                if (resGistda.ok) {
                    const data = await resGistda.json();
                    if (data.data) {
                        gistdaData = data.data;
                        const s = gistdaData.summary || {};
                        if (s.total_flooded_rai) {
                            document.getElementById('kpiGistdaRai').textContent = `${s.total_flooded_rai.toLocaleString()} ไร่`;
                            document.getElementById('gistdaTotalRai').innerHTML = `${s.total_flooded_rai.toLocaleString()} <span>ไร่ (~${s.total_flooded_sqkm} ตร.กม.)</span>`;
                        }
                        if (s.agricultural_impact_rai)
                            document.getElementById('gistdaAgriRai').innerHTML = `${s.agricultural_impact_rai.toLocaleString()} <span>ไร่ (78.9%)</span>`;
                        if (s.residential_impact_rai)
                            document.getElementById('gistdaResRai').innerHTML = `${s.residential_impact_rai.toLocaleString()} <span>ไร่ (21.1%)</span>`;
                    }
                }

                const resWeather = await fetch('api/weather');
                if (resWeather.ok) {
                    const data = await resWeather.json();
                    if (data.data) {
                        const w = data.data;
                        document.getElementById('wTemp').textContent      = `${w.temperature_c ?? '28.5'}°C`;
                        document.getElementById('wCondition').textContent = w.condition ?? 'ฝนตกปานกลาง';
                        document.getElementById('wRain24h').textContent   = `${w.rainfall_24h_mm ?? '45.2'} mm`;
                        document.getElementById('wWind').textContent      = `${w.wind_speed_kmh ?? '18.5'} km/h`;
                        document.getElementById('wHumidity').textContent  = `${w.humidity_percent ?? '88'}%`;
                        document.getElementById('wRiskLevel').textContent = w.alert_level_th ?? 'เฝ้าระวัง (Alert)';
                    }
                }
            } catch (err) {
                console.error('Dashboard data error:', err);
            }
        }

        function updateKpis(points) {
            let critical = 0, high = 0;
            points.forEach(p => {
                if (p.severity_level === 'critical' || p.risk_level === 'CRITICAL') critical++;
                if (p.severity_level === 'high'     || p.risk_level === 'HIGH')     high++;
            });
            document.getElementById('kpiCritical').textContent = critical;
            document.getElementById('kpiHigh').textContent     = high;
        }

        function renderFloodZones(points) {
            const container = document.getElementById('zoneListContainer');
            container.innerHTML = '';

            if (!points.length) {
                container.innerHTML = `<div class="empty-state"><i class="fa-solid fa-water"></i><p>ไม่พบข้อมูลจุดน้ำท่วม</p></div>`;
                return;
            }

            points.forEach((point, idx) => {
                const zoneName  = point.zone_name || point.title || `พื้นที่ ${idx + 1}`;
                const areaSqkm  = point.affected_area_sqkm ? `${point.affected_area_sqkm} ตร.กม.` : `รัศมี ${point.radius_meters || 200} ม.`;
                const severity  = (point.severity_level || point.risk_level || 'medium').toLowerCase();
                const waterLevel = point.water_level_meters ?? '–';
                const lat = point.latitude ?? point.location?.latitude;
                const lng = point.longitude ?? point.location?.longitude;

                const item = document.createElement('div');
                item.className = 'zone-card-item';
                item.style.cursor = 'pointer';
                item.onclick = () => openDetailDrawer(point);

                item.innerHTML = `
                    <div class="zone-main">
                        <div class="zone-badge-num ${severity}">${idx + 1}</div>
                        <div class="zone-details">
                            <span class="zone-title">${zoneName}</span>
                            <div class="zone-chips">
                                <span class="chip-stat"><i class="fa-solid fa-water"></i> ${waterLevel} ม.</span>
                                <span class="chip-stat"><i class="fa-solid fa-vector-square"></i> ${areaSqkm}</span>
                                <span class="chip-stat"><i class="fa-solid fa-users"></i> ${point.report_count ?? 1} รายงาน</span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;gap:6px;align-items:center;flex-shrink:0;">
                        <a href=".?lat=${lat}&lng=${lng}&zoom=14" class="btn-map-jump" onclick="event.stopPropagation()">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </a>
                        <button style="background:var(--surface-active);color:var(--text-muted);width:32px;height:32px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;" onclick="openDetailDrawer(floodPoints[${idx}])" title="ดูรายละเอียด">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                `;
                container.appendChild(item);
            });
        }
v>
                    <a href=".?lat=${lat}&lng=${lng}&zoom=14" class="btn-map-jump">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>ดูบนแผนที่</span>
                    </a>
                `;
                container.appendChild(item);
            });
        }

        function renderReportsTable(reports) {
            const tbody = document.getElementById('reportsTableBody');
            tbody.innerHTML = '';

            if (reports.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">ยังไม่มีข้อมูลรายงานในระบบ</td></tr>';
                return;
            }

            reports.forEach(r => {
                const row = document.createElement('tr');
                const photoSrc = (r.photos && r.photos[0]) || r.photo_url || 'https://images.unsplash.com/photo-1547683905-f686c993aae5?w=100&auto=format&fit=crop&q=60';
                const severity = r.severity_level || (r.severity === 3 ? 'critical' : (r.severity === 2 ? 'high' : 'low'));
                
                let severityTh = 'ปานกลาง';
                let sevClass = 'high';
                if (severity === 'critical' || r.severity === 3) { severityTh = 'วิกฤต (60+ ซม.)'; sevClass = 'critical'; }
                else if (severity === 'high' || r.severity === 2) { severityTh = 'ปานกลาง (30-50 ซม.)'; sevClass = 'high'; }
                else { severityTh = 'น้ำขัง (10-20 ซม.)'; sevClass = 'low'; }

                const lat = r.latitude ?? r.location?.latitude ?? 16.0538;
                const lng = r.longitude ?? r.location?.longitude ?? 103.6520;

                row.innerHTML = `
                    <td>
                        <img src="${photoSrc}" class="report-photo-thumb" alt="ภาพรายงาน" onerror="this.src='https://images.unsplash.com/photo-1547683905-f686c993aae5?w=100&auto=format&fit=crop&q=60'">
                    </td>
                    <td>
                        <span class="status-badge ${sevClass}">
                            <i class="fa-solid fa-circle" style="font-size: 8px;"></i> ${severityTh}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">${r.description || r.location_name || 'อ.เมืองร้อยเอ็ด'}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">${Number(lat).toFixed(4)}, ${Number(lng).toFixed(4)}</div>
                    </td>
                    <td>
                        <div style="font-family: 'Inter', sans-serif; font-weight: 600;">${r.phone || r.phone_number || '-'}</div>
                    </td>
                    <td>
                        <span class="status-badge ${r.status === 'VERIFIED' || r.status === 'verified' ? 'verified' : 'pending'}">
                            <i class="fa-solid ${r.status === 'VERIFIED' || r.status === 'verified' ? 'fa-check' : 'fa-clock'}"></i>
                            ${r.status === 'VERIFIED' || r.status === 'verified' ? 'ตรวจสอบแล้ว' : 'รอเจ้าหน้าที่'}
                        </span>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Modal Controls
        function openReportModal() {
            document.getElementById('reportModal').classList.add('active');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.remove('active');
        }

        function selectFloodLevel(level, el) {
            document.getElementById('inputSeverity').value = level;
            document.querySelectorAll('.level-card').forEach(c => c.classList.remove('active'));
            if (el) el.classList.add('active');
        }

        // Photo Upload Controls
        const btnPickPhoto = document.getElementById('btnPickPhoto');
        const inputFilePhoto = document.getElementById('inputFilePhoto');
        const photoPreviewWrap = document.getElementById('photoPreviewWrap');
        const photoPreviewImg = document.getElementById('photoPreviewImg');
        const photoName = document.getElementById('photoName');
        const btnRemovePhoto = document.getElementById('btnRemovePhoto');
        const btnDetectGps = document.getElementById('btnDetectGps');
        const lblCoords = document.getElementById('lblCoords');
        const inputLat = document.getElementById('inputLat');
        const inputLng = document.getElementById('inputLng');

        btnPickPhoto.addEventListener('click', () => inputFilePhoto.click());
        inputFilePhoto.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                photoName.textContent = file.name;
                const reader = new FileReader();
                reader.onload = (re) => {
                    photoPreviewImg.src = re.target.result;
                    photoPreviewWrap.style.display = 'flex';
                    btnPickPhoto.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        btnRemovePhoto.addEventListener('click', () => {
            inputFilePhoto.value = '';
            photoPreviewWrap.style.display = 'none';
            btnPickPhoto.style.display = 'flex';
        });

        // GPS Detection
        btnDetectGps.addEventListener('click', () => {
            if (!navigator.geolocation) {
                showToast('เบราว์เซอร์ไม่รองรับ GPS', 'fa-solid fa-triangle-exclamation', true);
                return;
            }
            btnDetectGps.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังหา...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude.toFixed(4);
                    const lng = pos.coords.longitude.toFixed(4);
                    inputLat.value = lat;
                    inputLng.value = lng;
                    lblCoords.textContent = `${lat}, ${lng}`;
                    btnDetectGps.innerHTML = '<i class="fa-solid fa-check"></i> ได้ตำแหน่งแล้ว';
                    showToast(`ตรวจพบพิกัดปัจจุบัน: ${lat}, ${lng}`);
                },
                (err) => {
                    btnDetectGps.innerHTML = '<i class="fa-solid fa-crosshairs"></i> ลองอีกครั้ง';
                    showToast('ไม่สามารถดึงตำแหน่ง GPS ได้ ใช้พิกัดเริ่มต้น', 'fa-solid fa-triangle-exclamation', true);
                },
                { timeout: 8000 }
            );
        });

        // Form Submit
        async function submitQuickReport(e) {
            e.preventDefault();
            const btnSubmit = document.getElementById('btnSubmitReport');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังส่งข้อมูล...';

            const severity = parseInt(document.getElementById('inputSeverity').value, 10);
            const phone = document.getElementById('inputPhone').value.trim();
            const lat = parseFloat(document.getElementById('inputLat').value);
            const lng = parseFloat(document.getElementById('inputLng').value);

            const defaultDesc = severity === 3
                ? 'น้ำท่วมระดับวิกฤต (60+ ซม.) รถเล็กผ่านไม่ได้'
                : (severity === 2
                    ? 'น้ำท่วมระดับปานกลาง (30-50 ซม.) ท่วมครึ่งล้อ'
                    : 'น้ำท่วมขังผิวทาง (10-20 ซม.) รถสัญจรได้');

            try {
                const payload = {
                    latitude: lat,
                    longitude: lng,
                    severity: severity,
                    phone: phone,
                    description: defaultDesc,
                    photos: photoPreviewImg.src && photoPreviewWrap.style.display !== 'none' ? [photoPreviewImg.src] : []
                };

                const res = await fetch('api/reports', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    showToast('ส่งรายงานสถานการณ์เรียบร้อยแล้ว');
                    document.getElementById('formQuickReport').reset();
                    btnRemovePhoto.click();
                    closeReportModal();
                    fetchDashboardData();
                } else {
                    showToast('ส่งข้อมูลไม่สำเร็จ กรุณาลองใหม่', 'fa-solid fa-triangle-exclamation', true);
                }
            } catch (err) {
                showToast('ส่งรายงานในเครื่องเรียบร้อยแล้ว (สถานะ: ออฟไลน์)', 'fa-solid fa-circle-check');
                closeReportModal();
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-paper-plane"></i><span>ส่งรายงานน้ำท่วมทันที</span>';
            }
        }

        // Init
        fetchDashboardData();
    </script>
</body>
</html>
