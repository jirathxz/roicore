<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROiCORE — แผนที่ติดตามและเฝ้าระวังสถานการณ์น้ำท่วม</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700;800&family=Noto+Sans+Thai:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
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

            /* Semantic Risk Colors */
            --risk-critical: #DC2626;
            --risk-critical-bg: #FEF2F2;
            --risk-high: #EA580C;
            --risk-high-bg: #FFF7ED;
            --risk-medium: #D97706;
            --risk-medium-bg: #FEF3C7;
            --risk-low: #2563EB;
            --risk-low-bg: #EFF6FF;

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

        /* Fullscreen Map */
        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
            background-color: #E2E8F0;
        }

        /* Top Overlay Navbar */
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
            display: flex;
            align-items: center;
            gap: 16px;
            pointer-events: auto;
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
            font-style: normal;
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

        .nav-tab i {
            font-size: 13px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
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

        /* Flood Zone Polygon & Tooltip */
        .custom-leaflet-tooltip {
            background-color: var(--surface) !important;
            border: none !important;
            border-radius: var(--r-md) !important;
            padding: 6px 10px !important;
            box-shadow: none !important;
            font-family: 'Noto Sans Thai', 'Inter', sans-serif !important;
            color: var(--text-main) !important;
            font-size: 12px !important;
        }

        .custom-leaflet-tooltip::before {
            display: none !important;
        }

        .zone-tooltip {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
        }

        .zone-tooltip.critical { color: var(--risk-critical); }
        .zone-tooltip.high { color: var(--risk-high); }
        .zone-tooltip.medium { color: var(--risk-medium); }
        .zone-tooltip.low { color: var(--risk-low); }

        .flood-boundary-polygon {
            transition: fill-opacity 0.2s ease, stroke-width 0.2s ease;
            cursor: pointer;
        }

        .flood-boundary-polygon:hover {
            fill-opacity: 0.45 !important;
            stroke-width: 3.5px !important;
        }

        /* Floating Filter & Summary Bar (Bottom Left) */
        .bottom-panel {
            position: absolute;
            bottom: 20px;
            left: 16px;
            right: 16px;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 12px;
            pointer-events: none;
        }

        .filter-card {
            background-color: var(--surface);
            border-radius: var(--r-xl);
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: auto;
            max-width: 620px;
        }

        .filter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .filter-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-chips {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chip {
            background-color: var(--surface-subtle);
            color: var(--text-muted);
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: var(--r-full);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .chip:hover {
            background-color: var(--surface-active);
            color: var(--text-main);
        }

        .chip.active {
            background-color: var(--accent-blue);
            color: #FFFFFF;
        }

        .chip-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .stats-badge-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stat-item {
            background-color: var(--surface-subtle);
            padding: 6px 12px;
            border-radius: var(--r-md);
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-item.critical {
            background-color: var(--risk-critical-bg);
            color: var(--risk-critical);
            font-weight: 600;
        }

        .stat-item.high {
            background-color: var(--risk-high-bg);
            color: var(--risk-high);
            font-weight: 600;
        }

        /* Map Controls Repositioning & Clean Flat Styling */
        .leaflet-control-zoom {
            border: none !important;
            border-radius: var(--r-md) !important;
            overflow: hidden;
            background-color: var(--surface) !important;
        }

        .leaflet-control-zoom a {
            background-color: var(--surface) !important;
            color: var(--text-main) !important;
            border: none !important;
            width: 34px !important;
            height: 34px !important;
            line-height: 34px !important;
            font-size: 15px !important;
            transition: background-color 0.15s ease;
        }

        .leaflet-control-zoom a:hover {
            background-color: var(--surface-subtle) !important;
            color: var(--accent-blue) !important;
        }

        .leaflet-popup-content-wrapper {
            background-color: var(--surface) !important;
            border-radius: var(--r-lg) !important;
            padding: 0 !important;
            overflow: hidden;
            border: none !important;
        }

        .leaflet-popup-content {
            margin: 0 !important;
            line-height: 1.5 !important;
        }

        .leaflet-popup-tip-container {
            display: none !important;
        }

        /* Custom Popup Card */
        .point-popup-card {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 252px;
            max-width: 300px;
            font-family: 'Noto Sans Thai', 'Inter', sans-serif;
        }

        .point-popup-header {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .point-popup-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.3;
        }

        .risk-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--r-sm);
            width: fit-content;
        }

        .risk-pill.critical { background-color: var(--risk-critical-bg); color: var(--risk-critical); }
        .risk-pill.high { background-color: var(--risk-high-bg); color: var(--risk-high); }
        .risk-pill.medium { background-color: var(--risk-medium-bg); color: var(--risk-medium); }
        .risk-pill.low { background-color: var(--risk-low-bg); color: var(--risk-low); }

        .point-popup-details {
            background-color: var(--surface-subtle);
            border-radius: var(--r-md);
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 12px;
        }

        .detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--text-main);
        }

        .detail-label {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-weight: 600;
        }

        .detail-value.highlight {
            color: var(--risk-critical);
        }

        /* Marker Pin Styling */
        .custom-flood-pin {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .pin-outer {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 13px;
        }

        .pin-outer.critical { background-color: var(--risk-critical); }
        .pin-outer.high { background-color: var(--risk-high); }
        .pin-outer.medium { background-color: var(--risk-medium); }
        .pin-outer.low { background-color: var(--risk-low); }

        /* Modal Backdrop & Container */
        /* Modal Backdrop */
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

        /* Smart Location Chip */
        .smart-location-badge {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
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
            font-size: 13px;
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

        /* Level Cards Selector (Visual 1-Tap Hierarchy) */
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
            gap: 4px;
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

        /* Photo Upload Drop Area & Preview */
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

        .photo-preview-card {
            background-color: var(--surface-subtle);
            border-radius: var(--r-lg);
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .photo-preview-thumb {
            width: 44px;
            height: 44px;
            border-radius: var(--r-md);
            object-fit: cover;
            background-color: var(--surface-active);
        }

        .photo-preview-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
            overflow: hidden;
        }

        .photo-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .photo-status {
            font-size: 11px;
            color: var(--status-success);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-remove-photo {
            background-color: var(--risk-critical-bg);
            color: var(--risk-critical);
            width: 28px;
            height: 28px;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: opacity 0.15s ease;
        }

        .btn-remove-photo:hover {
            opacity: 0.8;
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

        /* GISTDA Satellite Tooltip */
        .gistda-tooltip {
            font-family: 'Noto Sans Thai', 'Inter', sans-serif;
            font-size: 12px;
            padding: 4px;
        }

        .gistda-tooltip-header {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        /* GISTDA Region Legend (Bottom Right) */
        .gistda-legend {
            position: absolute;
            bottom: 20px;
            right: 16px;
            z-index: 1000;
            background-color: var(--surface);
            border-radius: var(--r-lg);
            padding: 12px 14px;
            pointer-events: none;
            min-width: 164px;
        }

        .legend-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-rows {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-main);
            font-weight: 500;
        }

        .legend-swatch {
            width: 16px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
            border-width: 1.5px;
            border-style: dashed;
        }

        @media (max-width: 600px) {
            .gistda-legend { display: none; }
        }

        /* Crowd-sourced Zone Badge (ติดบน Popup) */
        .community-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background-color: #FFF7ED;
            color: #C2410C;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--r-sm);
            width: fit-content;
        }

        /* Crowd Zone Pulse Animation สำหรับจุดใหม่ที่รายงานบ่อย */
        @keyframes crowd-pulse {
            0%, 100% { fill-opacity: var(--base-opacity); }
            50% { fill-opacity: calc(var(--base-opacity) + 0.12); }
        }

        .crowd-sourced-polygon {
            animation: crowd-pulse 3s ease-in-out infinite;
            cursor: pointer;
        }

        /* Toast Notification */
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
            animation: fadeIn 0.2s ease;
        }

        @media (max-width: 900px) {
            .top-navbar {
                top: 10px;
                left: 10px;
                right: 10px;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .nav-card {
                padding: 10px 14px;
                justify-content: space-between;
                flex-wrap: wrap;
            }
            .nav-actions {
                justify-content: space-between;
                width: 100%;
            }
            .bottom-panel {
                left: 10px;
                right: 10px;
                bottom: 10px;
                flex-direction: column-reverse;
                align-items: stretch;
            }
            .filter-card {
                max-width: 100%;
                padding: 12px 14px;
            }
        }

        @media (max-width: 600px) {
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
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Top Floating Navigation (Fully Responsive) -->
        <header class="top-navbar">
            <div class="nav-card">
                <a href="." class="brand">RO<span class="accent">i</span>CORE</a>
                <div class="nav-divider"></div>
                <nav class="nav-menu">
                    <a href="." class="nav-tab active">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>แผนที่</span>
                    </a>
                    <a href="?page=dashboard" class="nav-tab">
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
                <div class="status-chip" id="systemStatusChip">
                    <i class="fa-solid fa-wifi-slash"></i>
                    <span>สถานะ: ออฟไลน์ (ข้อมูลจำลองในเครื่อง)</span>
                </div>

                <button class="btn-report-fab" onclick="openReportModal()">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>แจ้งเหตุน้ำท่วม</span>
                </button>
            </div>
        </header>

        <!-- Leaflet Map Container -->
        <main id="map"></main>

        <!-- Bottom Panel: Summary & Filters -->
        <div class="bottom-panel">
            <div class="filter-card">
                <div class="filter-header">
                    <div class="filter-title">
                        <i class="fa-solid fa-layer-group" style="color: var(--accent-blue);"></i>
                        ระดับการเตือนภัยและอาณาเขตน้ำท่วม
                    </div>
                    <div class="stats-badge-group">
                        <div class="stat-item critical" id="statCritical">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span id="criticalCount">1 จุดวิกฤต</span>
                        </div>
                        <div class="stat-item high" id="statHigh">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span id="highCount">2 จุดเสี่ยงสูง</span>
                        </div>
                    </div>
                </div>

                <div class="filter-chips">
                    <button class="chip active" onclick="filterMarkers('ALL', this)">
                        ทั้งหมด (<span id="totalPointsCount">5</span>)
                    </button>
                    <button class="chip" onclick="filterMarkers('CRITICAL', this)">
                        <span class="chip-dot" style="background-color: var(--risk-critical);"></span>
                        วิกฤต
                    </button>
                    <button class="chip" onclick="filterMarkers('HIGH', this)">
                        <span class="chip-dot" style="background-color: var(--risk-high);"></span>
                        ความเสี่ยงสูง
                    </button>
                    <button class="chip" onclick="filterMarkers('MEDIUM', this)">
                        <span class="chip-dot" style="background-color: var(--risk-medium);"></span>
                        ความเสี่ยงปานกลาง
                    </button>
                    <button class="chip" onclick="filterMarkers('LOW', this)">
                        <span class="chip-dot" style="background-color: var(--risk-low);"></span>
                        ความเสี่ยงต่ำ
                    </button>
                    <button class="chip active" id="btnToggleZones" onclick="toggleZonesLayer(this)" style="background-color: var(--accent-tint); color: var(--accent-blue);">
                        <i class="fa-solid fa-draw-polygon"></i>
                        <span>แสดงอาณาเขต</span>
                    </button>
                    <button class="chip active" id="btnToggleGistda" onclick="toggleGistdaLayer(this)" style="background-color: #EDE9FE; color: #6D28D9; font-weight: 700;">
                        <i class="fa-solid fa-satellite"></i>
                        <span>ดาวเทียม GISTDA (<span id="gistdaAreaLabel">กำลังโหลด...</span>)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- GISTDA Region Color Legend -->
    <aside class="gistda-legend" id="gistdaLegend">
        <div class="legend-title">
            <i class="fa-solid fa-satellite" style="color: #7C3AED;"></i>
            GISTDA ดาวเทียม
        </div>
        <div class="legend-rows">
            <div class="legend-row">
                <div class="legend-swatch" style="background-color: #BAE6FD; border-color: #0369A1;"></div>
                ภาคเหนือ
            </div>
            <div class="legend-row">
                <div class="legend-swatch" style="background-color: #DDD6FE; border-color: #7C3AED;"></div>
                ภาคอีสาน
            </div>
            <div class="legend-row">
                <div class="legend-swatch" style="background-color: #CCFBF1; border-color: #0F766E;"></div>
                ภาคกลาง
            </div>
            <div class="legend-row">
                <div class="legend-swatch" style="background-color: #FEF3C7; border-color: #B45309;"></div>
                ภาคใต้
            </div>
            <div class="legend-row">
                <div class="legend-swatch" style="background-color: #BBF7D0; border-color: #15803D;"></div>
                ภาคตะวันออก
            </div>
        </div>
    </aside>

    <!-- Quick Report Modal (Compact & Fastest Flow) -->
    <div class="modal-backdrop" id="reportModal" onclick="handleBackdropClick(event)">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fa-solid fa-bullhorn" style="color: var(--accent-blue);"></i>
                    แจ้งเหตุน้ำท่วมด่วน
                </h2>
                <button class="btn-close" onclick="closeReportModal()" title="ปิดหน้าต่าง">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Smart Location Detection Indicator -->
            <div class="smart-location-badge">
                <div class="smart-location-text">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    <span>พิกัด: <strong id="quickLocLabel">16.0538, 103.6520</strong></span>
                </div>
                <button type="button" class="btn-detect-gps" onclick="detectGPSLocation()">
                    <i class="fa-solid fa-crosshairs"></i> ใช้พิกัดปัจจุบัน
                </button>
            </div>

            <form id="quickReportForm" onsubmit="handleQuickReport(event)">
                <!-- Hidden Coordinates -->
                <input type="hidden" id="inputLat" value="16.0538">
                <input type="hidden" id="inputLng" value="103.6520">
                <input type="hidden" id="inputSeverity" value="3">

                <!-- Field 1: Flood Water Level (3 Visual 1-Tap Cards) -->
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
                                    <div class="level-sub">น้ำขังผิวทาง / รถสัญจรได้</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check level-check-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Field 2: Phone Number (with auto-memory) -->
                <div class="form-group">
                    <label class="form-label" for="inputPhone">
                        <i class="fa-solid fa-phone"></i> เบอร์โทรศัพท์ติดต่อ
                    </label>
                    <input class="form-input" type="tel" id="inputPhone" placeholder="ระบุเบอร์โทรศัพท์ (เช่น 0812345678)" required maxlength="15">
                </div>

                <!-- Field 3: Photo Attachment / Camera Capture -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-camera"></i> ภาพถ่ายสถานการณ์ (ถ้ามี)
                    </label>
                    <div class="photo-picker-wrap" id="photoDropArea" onclick="document.getElementById('photoInput').click()">
                        <i class="fa-solid fa-camera photo-picker-icon"></i>
                        <div class="photo-picker-text">
                            <span class="main-text">แตะเพื่อถ่ายรูป หรือเลือกภาพถ่าย</span>
                            <span class="sub-text">รองรับกล้องมือถือและไฟล์ภาพ</span>
                        </div>
                        <input type="file" id="photoInput" accept="image/*" capture="environment" style="display: none;" onchange="handlePhotoUpload(event)">
                    </div>
                    <div class="photo-preview-card" id="photoPreviewCard" style="display: none;">
                        <img id="photoPreviewImg" src="" alt="ภาพถ่ายน้ำท่วม" class="photo-preview-thumb">
                        <div class="photo-preview-meta">
                            <span class="photo-name" id="photoFileName">photo.jpg</span>
                            <span class="photo-status"><i class="fa-solid fa-circle-check"></i> แนบภาพถ่ายแล้ว</span>
                        </div>
                        <button type="button" class="btn-remove-photo" onclick="removeSelectedPhoto(event)" title="ลบภาพ">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmitReport" style="margin-top: 4px;">
                    <i class="fa-solid fa-paper-plane"></i>
                    ส่งรายงานน้ำท่วมทันที
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // Thailand national center (zoom-out to see all provinces)
        const THAILAND_CENTER = [13.0, 101.5];
        const ROI_ET_CENTER = [16.0538, 103.6520];
        let map;
        let floodPoints = [];
        let mapMarkers = [];
        let mapCircles = [];
        let mapPolygons = [];
        let gistdaPolygons = [];
        let showPolygons = true;
        let showGistda = true;
        let currentFilter = 'ALL';
        let currentAttachedPhoto = null;

        // GISTDA Region Color Palette
        const GISTDA_REGION_COLORS = {
            north:     { stroke: '#0369A1', fill: '#0EA5E9' },   // Sky Blue
            northeast: { stroke: '#7C3AED', fill: '#8B5CF6' },   // Purple
            central:   { stroke: '#0F766E', fill: '#14B8A6' },   // Teal
            south:     { stroke: '#B45309', fill: '#F59E0B' },   // Amber
            east:      { stroke: '#15803D', fill: '#22C55E' },   // Green
        };

        const RISK_OPACITY = { critical: 0.38, high: 0.28, medium: 0.20, low: 0.14 };

        // Built-in offline fallback points if network is unreachable
        const fallbackFloodPoints = [
            {
                "id": "d3b07384-d113-4c91-9c62-124b89f53e01",
                "title": "สะพานข้ามแม่น้ำชี (ธวัชบุรี)",
                "location": { "latitude": 16.0538, "longitude": 103.6520 },
                "radius_meters": 250,
                "water_level_meters": 2.85,
                "risk_level": "CRITICAL",
                "risk_level_label": "ความเสี่ยงวิกฤต",
                "report_count": 5,
                "updated_at": new Date().toISOString(),
                "polygon_coordinates": [
                    [16.0650, 103.6410],
                    [16.0680, 103.6560],
                    [16.0620, 103.6690],
                    [16.0480, 103.6650],
                    [16.0430, 103.6500],
                    [16.0470, 103.6380]
                ],
                "zone_name": "เขตพื้นที่ลุ่มน้ำชีเอ่อท่วม ธวัชบุรี",
                "affected_area_sqkm": 2.45
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
                "updated_at": new Date().toISOString(),
                "polygon_coordinates": [
                    [16.0440, 103.7780],
                    [16.0470, 103.7950],
                    [16.0390, 103.8030],
                    [16.0270, 103.7940],
                    [16.0280, 103.7810]
                ],
                "zone_name": "เขตพื้นที่ล้นคลองส่งน้ำเสลภูมิ",
                "affected_area_sqkm": 1.35
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
                "updated_at": new Date().toISOString(),
                "polygon_coordinates": [
                    [16.0740, 103.6780],
                    [16.0760, 103.6920],
                    [16.0670, 103.6960],
                    [16.0610, 103.6870],
                    [16.0630, 103.6780]
                ],
                "zone_name": "แนวท่วมขังผิวถนนเลี่ยงเมือง",
                "affected_area_sqkm": 0.75
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
                "updated_at": new Date().toISOString(),
                "polygon_coordinates": [
                    [16.0220, 103.7110],
                    [16.0250, 103.7290],
                    [16.0150, 103.7350],
                    [16.0030, 103.7230],
                    [16.0070, 103.7100]
                ],
                "zone_name": "พื้นที่เฝ้าระวังอ่างธวัชชัย",
                "affected_area_sqkm": 1.10
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
                "updated_at": new Date().toISOString(),
                "polygon_coordinates": [
                    [16.3120, 103.9740],
                    [16.3160, 103.9900],
                    [16.3080, 103.9990],
                    [16.2920, 103.9930],
                    [16.2900, 103.9800]
                ],
                "zone_name": "เขตตลิ่งลำน้ำยัง โพนทอง",
                "affected_area_sqkm": 1.85
            }
        ];

        // Initialize Map
        function initMap() {
            map = L.map('map', {
                center: THAILAND_CENTER,
                zoom: 6,
                zoomControl: true,
                minZoom: 5
            });

            // OpenStreetMap Standard Tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            // On Map Click -> Update quick report coordinates
            map.on('click', function(e) {
                syncCoordinates(e.latlng.lat, e.latlng.lng);
            });

            // Load remembered phone number if available
            const savedPhone = localStorage.getItem('roicore_phone');
            if (savedPhone) {
                const phoneInput = document.getElementById('inputPhone');
                if (phoneInput) phoneInput.value = savedPhone;
            }

            // Load flood points and GISTDA national satellite data
            loadFloodPoints();
            loadGistdaData();
        }

        function toggleZonesLayer(btn) {
            showPolygons = !showPolygons;
            if (btn) {
                btn.classList.toggle('active', showPolygons);
                btn.style.backgroundColor = showPolygons ? 'var(--accent-tint)' : 'var(--surface-subtle)';
                btn.style.color = showPolygons ? 'var(--accent-blue)' : 'var(--text-muted)';
            }
            mapPolygons.forEach(p => {
                if (showPolygons) {
                    map.addLayer(p);
                } else {
                    map.removeLayer(p);
                }
            });
        }

        function toggleGistdaLayer(btn) {
            showGistda = !showGistda;
            if (btn) {
                btn.classList.toggle('active', showGistda);
                btn.style.backgroundColor = showGistda ? '#EDE9FE' : 'var(--surface-subtle)';
                btn.style.color = showGistda ? '#6D28D9' : 'var(--text-muted)';
            }
            gistdaPolygons.forEach(p => {
                if (showGistda) {
                    map.addLayer(p);
                } else {
                    map.removeLayer(p);
                }
            });
        }

        async function loadGistdaData() {
            try {
                // ดึงข้อมูลระดับประเทศ ทุกจังหวัดที่ได้รับผลกระทบ
                const res = await fetch('?api=gistda/flood&all=1');
                const json = await res.json();
                if (!json.data || !json.data.satellite_flood_polygons) return;

                const polygons = json.data.satellite_flood_polygons;
                const nationalSummary = json.data.national_summary || json.data.summary || {};
                const totalAffected = json.data.total_provinces_affected || 0;

                // อัปเดต label ใน chip ให้แสดงสถิติระดับประเทศ
                const label = document.getElementById('gistdaAreaLabel');
                if (label) {
                    const rai = nationalSummary.total_flooded_rai;
                    label.textContent = rai
                        ? `${(rai / 1000).toFixed(0)}K ไร่ · ${totalAffected} จังหวัด`
                        : `${totalAffected} จังหวัด`;
                }

                // วาด GISTDA Satellite Polygons ทุกจังหวัดด้วยสีตาม region
                polygons.forEach(polyData => {
                    const region = polyData.region || 'northeast';
                    const riskLevel = polyData.risk_level || 'medium';
                    const colors = GISTDA_REGION_COLORS[region] || GISTDA_REGION_COLORS.northeast;
                    const fillOpacity = RISK_OPACITY[riskLevel] || 0.22;

                    const poly = L.polygon(polyData.coordinates, {
                        color: colors.stroke,
                        fillColor: colors.fill,
                        fillOpacity: fillOpacity,
                        weight: riskLevel === 'critical' ? 2.4 : 1.8,
                        dashArray: '6, 4',
                        className: 'gistda-satellite-polygon'
                    });

                    const regionLabel = {
                        north: 'ภาคเหนือ', northeast: 'ภาคอีสาน',
                        central: 'ภาคกลาง', south: 'ภาคใต้', east: 'ภาคตะวันออก'
                    }[region] || region;

                    const riskLabel = {
                        critical: '🔴 วิกฤต', high: '🟠 เสี่ยงสูง',
                        medium: '🟡 ปานกลาง', low: '🔵 ต่ำ'
                    }[riskLevel] || riskLevel;

                    const strokeColor = colors.stroke;

                    poly.bindTooltip(`
                        <div class="gistda-tooltip">
                            <div class="gistda-tooltip-header" style="color: ${strokeColor};">
                                <i class="fa-solid fa-satellite"></i>
                                <span>GISTDA · ${regionLabel}</span>
                            </div>
                            <div style="font-weight: 700; color: var(--text-main); margin-bottom: 4px; font-size: 12.5px;">
                                ${polyData.name}
                            </div>
                            <div style="color: var(--text-muted); font-size: 11px; display: flex; gap: 8px;">
                                <span>${riskLabel}</span>
                                <span>&bull;</span>
                                <span style="color: ${strokeColor}; font-weight: 600;">${polyData.area_rai.toLocaleString()} ไร่</span>
                            </div>
                            ${polyData.province ? `<div style="font-size: 10.5px; color: var(--text-muted); margin-top: 3px;"><i class="fa-solid fa-location-dot"></i> จ.${polyData.province}</div>` : ''}
                            <div style="font-size: 10px; color: #94A3B8; margin-top: 2px;">
                                Sentinel-1 SAR &bull; GISTDA Open API
                            </div>
                        </div>
                    `, { sticky: true, className: 'custom-leaflet-tooltip' });

                    if (showGistda) poly.addTo(map);
                    gistdaPolygons.push(poly);
                });

                console.info(`[GISTDA] โหลดข้อมูลน้ำท่วมดาวเทียม ${polygons.length} พื้นที่ ใน ${totalAffected} จังหวัด`);
            } catch (err) {
                console.warn('[GISTDA] ข้อมูลดาวเทียม fallback:', err);
            }
        }

        function syncCoordinates(lat, lng) {
            const latVal = typeof lat === 'number' ? lat.toFixed(6) : lat;
            const lngVal = typeof lng === 'number' ? lng.toFixed(6) : lng;
            const latInput = document.getElementById('inputLat');
            const lngInput = document.getElementById('inputLng');
            const label = document.getElementById('quickLocLabel');
            if (latInput) latInput.value = latVal;
            if (lngInput) lngInput.value = lngVal;
            if (label) label.textContent = `${parseFloat(latVal).toFixed(4)}, ${parseFloat(lngVal).toFixed(4)}`;
        }

        function detectGPSLocation() {
            if (!navigator.geolocation) {
                showToast('เบราว์เซอร์ไม่รองรับการระบุพิกัด GPS', 'fa-triangle-exclamation');
                return;
            }

            const gpsBtn = document.querySelector('.btn-detect-gps');
            if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ค้นหาพิกัด...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    syncCoordinates(lat, lng);
                    map.flyTo([lat, lng], 14);
                    if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-circle-check"></i> ได้พิกัดแล้ว';
                    setTimeout(() => {
                        if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-crosshairs"></i> ใช้พิกัดปัจจุบัน';
                    }, 2000);
                },
                (err) => {
                    showToast('ไม่สามารถระบุพิกัดได้ ใช้พิกัดศูนย์กลางแผนที่แทน', 'fa-circle-info');
                    if (gpsBtn) gpsBtn.innerHTML = '<i class="fa-solid fa-crosshairs"></i> ใช้พิกัดปัจจุบัน';
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        }

        function selectFloodLevel(level, element) {
            document.getElementById('inputSeverity').value = level;
            document.querySelectorAll('.level-card').forEach(c => c.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
        }

        function handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                showToast('ขนาดไฟล์รูปภาพเกิน 5 MB', 'fa-triangle-exclamation');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                currentAttachedPhoto = e.target.result;
                document.getElementById('photoPreviewImg').src = currentAttachedPhoto;
                document.getElementById('photoFileName').textContent = file.name || 'photo.jpg';
                document.getElementById('photoDropArea').style.display = 'none';
                document.getElementById('photoPreviewCard').style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }

        function removeSelectedPhoto(event) {
            if (event) event.stopPropagation();
            currentAttachedPhoto = null;
            document.getElementById('photoInput').value = '';
            document.getElementById('photoPreviewImg').src = '';
            document.getElementById('photoPreviewCard').style.display = 'none';
            document.getElementById('photoDropArea').style.display = 'flex';
        }

        function getRiskColor(level) {
            switch(level) {
                case 'CRITICAL': return '#DC2626';
                case 'HIGH': return '#EA580C';
                case 'MEDIUM': return '#D97706';
                case 'LOW': return '#2563EB';
                default: return '#2563EB';
            }
        }

        function getRiskIcon(level) {
            switch(level) {
                case 'CRITICAL': return 'fa-triangle-exclamation';
                case 'HIGH': return 'fa-circle-exclamation';
                case 'MEDIUM': return 'fa-water';
                case 'LOW': return 'fa-circle-info';
                default: return 'fa-location-dot';
            }
        }

        // ─── Crowd-Sourced Zone Helpers ─────────────────────────────────────────

        /**
         * คำนวณระยะทาง Haversine ระหว่าง 2 จุด (เมตร)
         */
        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        /**
         * สร้าง Polygon รูป Octagon รอบจุดศูนย์กลาง
         * @param {number} lat - ละติจูดศูนย์กลาง
         * @param {number} lng - ลองจิจูดศูนย์กลาง
         * @param {number} radiusM - รัศมีเป็นเมตร
         * @param {number} sides - จำนวนด้าน (ค่าเริ่มต้น 8)
         * @returns {Array} พิกัด [lat, lng][]
         */
        function generateOctagonCoords(lat, lng, radiusM, sides = 8) {
            const R = 6371000;
            const latRad = lat * Math.PI / 180;
            return Array.from({ length: sides }, (_, i) => {
                const angle = (2 * Math.PI * i) / sides - Math.PI / 2;
                const dLat = (radiusM / R) * Math.cos(angle) * (180 / Math.PI);
                const dLng = (radiusM / (R * Math.cos(latRad))) * Math.sin(angle) * (180 / Math.PI);
                return [lat + dLat, lng + dLng];
            });
        }

        /**
         * คำนวณรัศมีอาณาเขตตามจำนวนรายงาน (เมตร)
         */
        function getCrowdZoneRadius(reportCount, baseRadius) {
            const base = baseRadius || 200;
            if (reportCount >= 10) return base * 2.5;   // 10+ รายงาน: ขนาดใหญ่มาก
            if (reportCount >= 7)  return base * 2.0;   // 7-9 รายงาน: ขนาดใหญ่
            if (reportCount >= 5)  return base * 1.6;   // 5-6 รายงาน: ขนาดปานกลางใหญ่
            return base * 1.2;                          // 3-4 รายงาน: เริ่มขึ้นอาณาเขต
        }

        /**
         * กำหนดสไตล์ Crowd Zone ตามจำนวนรายงาน
         */
        function getCrowdZoneStyle(reportCount, riskColor) {
            if (reportCount >= 10) return { weight: 3.0, fillOpacity: 0.42, dashArray: null };
            if (reportCount >= 7)  return { weight: 2.5, fillOpacity: 0.36, dashArray: '8, 3' };
            if (reportCount >= 5)  return { weight: 2.2, fillOpacity: 0.30, dashArray: '6, 4' };
            return                        { weight: 1.8, fillOpacity: 0.22, dashArray: '4, 6' };
        }

        /**
         * สร้าง label บอกสถานะ crowd zone
         */
        function getCrowdZoneLabel(reportCount) {
            if (reportCount >= 10) return { icon: '🔴', text: `วิกฤต! ชุมชนรายงาน ${reportCount} ครั้ง` };
            if (reportCount >= 7)  return { icon: '🟠', text: `เสี่ยงสูง ชุมชนรายงาน ${reportCount} ครั้ง` };
            if (reportCount >= 5)  return { icon: '🟡', text: `พื้นที่เฝ้าระวังชุมชน (${reportCount} ครั้ง)` };
            return                        { icon: '👥', text: `ชุมชนรายงาน ${reportCount} ครั้ง` };
        }

        async function loadFloodPoints() {
            try {
                const res = await fetch('?api=points');
                const json = await res.json();
                floodPoints = (json.data && json.data.length > 0) ? json.data : fallbackFloodPoints;
                updateStatusChip(json.is_offline ?? true);
            } catch (err) {
                floodPoints = fallbackFloodPoints;
                updateStatusChip(true);
            }

            renderMarkers();
            updateStatistics();
        }

        function updateStatusChip(isOffline) {
            const chip = document.getElementById('systemStatusChip');
            if (!chip) return;
            if (isOffline) {
                chip.innerHTML = '<i class="fa-solid fa-wifi-slash" style="color: #D97706;"></i> <span>สถานะ: ออฟไลน์ (ข้อมูลจำลองในเครื่อง)</span>';
            } else {
                chip.innerHTML = '<i class="fa-solid fa-wifi" style="color: #16A34A;"></i> <span>สถานะ: ออนไลน์</span>';
            }
        }

        function clearMapLayers() {
            mapMarkers.forEach(m => map.removeLayer(m));
            mapCircles.forEach(c => map.removeLayer(c));
            mapPolygons.forEach(p => map.removeLayer(p));
            mapMarkers = [];
            mapCircles = [];
            mapPolygons = [];
        }

        function renderMarkers() {
            clearMapLayers();

            const filtered = floodPoints.filter(p => {
                if (currentFilter === 'ALL') return true;
                return p.risk_level === currentFilter;
            });

            filtered.forEach(point => {
                const lat = point.location.latitude;
                const lng = point.location.longitude;
                const riskLevel = point.risk_level;
                const riskColor = getRiskColor(riskLevel);
                const riskIcon = getRiskIcon(riskLevel);
                const isCritical = riskLevel === 'CRITICAL';
                const isHigh = riskLevel === 'HIGH';

                // Check for attached photo
                let photoHtml = '';
                if (point.photos && point.photos.length > 0 && point.photos[0]) {
                    photoHtml = `
                        <div style="margin-top: 6px; border-radius: 8px; overflow: hidden; max-height: 120px;">
                            <img src="${point.photos[0]}" alt="รูปถ่ายน้ำท่วม" style="width: 100%; height: 100px; object-fit: cover; display: block;">
                        </div>
                    `;
                }

                // Popup Content with Area and Zone Info
                const areaInfoHtml = point.affected_area_sqkm ? `
                    <div class="detail-row">
                        <span class="detail-label"><i class="fa-solid fa-draw-polygon"></i> อาณาเขตพื้นที่</span>
                        <span class="detail-value">${point.affected_area_sqkm} ตร.กม.</span>
                    </div>
                ` : `
                    <div class="detail-row">
                        <span class="detail-label"><i class="fa-solid fa-circle-radiation"></i> รัศมีผลกระทบ</span>
                        <span class="detail-value">${point.radius_meters} เมตร</span>
                    </div>
                `;

                // Popup badge เพิ่มเติมสำหรับ crowd-sourced zone
                const crowdBadgeHtml = (!point.polygon_coordinates && point.report_count >= 3)
                    ? (() => { const lbl = getCrowdZoneLabel(point.report_count); return `<div class="community-badge"><i class="fa-solid fa-users"></i> ${lbl.text}</div>`; })()
                    : '';

                const popupContent = `
                    <div class="point-popup-card">
                        <div class="point-popup-header">
                            <div class="risk-pill ${riskLevel.toLowerCase()}">
                                <i class="fa-solid ${riskIcon}"></i>
                                <span>${point.risk_level_label || riskLevel}</span>
                            </div>
                            ${crowdBadgeHtml}
                            <h3 class="point-popup-title">${point.title}</h3>
                            ${point.zone_name ? `<span style="font-size: 11.5px; color: var(--text-muted);"><i class="fa-solid fa-location-dot"></i> ${point.zone_name}</span>` : ''}
                        </div>

                        ${photoHtml}

                        <div class="point-popup-details">
                            <div class="detail-row">
                                <span class="detail-label"><i class="fa-solid fa-water"></i> ระดับน้ำ</span>
                                <span class="detail-value highlight">${point.water_level_meters} เมตร</span>
                            </div>
                            ${areaInfoHtml}
                            <div class="detail-row">
                                <span class="detail-label"><i class="fa-solid fa-users"></i> จำนวนรายงาน</span>
                                <span class="detail-value">${point.report_count} ครั้ง</span>
                            </div>
                        </div>
                    </div>
                `;

                // 1. Draw Flood Zone Polygon
                if (point.polygon_coordinates && point.polygon_coordinates.length >= 3) {
                    // ─ Official polygon (จากระบบหรือ API) ─────────────────────
                    const poly = L.polygon(point.polygon_coordinates, {
                        color: riskColor,
                        fillColor: riskColor,
                        fillOpacity: isCritical ? 0.38 : (isHigh ? 0.28 : 0.20),
                        weight: isCritical ? 2.5 : 1.8,
                        dashArray: isCritical ? '6, 4' : null,
                        className: 'flood-boundary-polygon'
                    });

                    if (showPolygons) poly.addTo(map);

                    poly.bindTooltip(`
                        <div class="zone-tooltip ${riskLevel.toLowerCase()}">
                            <i class="fa-solid fa-draw-polygon"></i>
                            <span><strong>${point.zone_name || point.title}</strong> (${point.water_level_meters} ม.)</span>
                        </div>
                    `, { sticky: true, className: 'custom-leaflet-tooltip', direction: 'top' });

                    poly.bindPopup(popupContent);
                    mapPolygons.push(poly);

                } else if (point.report_count >= 3) {
                    // ─ Crowd-sourced Auto Zone (จากจำนวนรายงานสะสม) ──────────
                    const crowdRadius = getCrowdZoneRadius(point.report_count, point.radius_meters);
                    const crowdCoords = generateOctagonCoords(lat, lng, crowdRadius);
                    const crowdStyle = getCrowdZoneStyle(point.report_count, riskColor);
                    const crowdLabel = getCrowdZoneLabel(point.report_count);

                    const crowdPoly = L.polygon(crowdCoords, {
                        color: riskColor,
                        fillColor: riskColor,
                        fillOpacity: crowdStyle.fillOpacity,
                        weight: crowdStyle.weight,
                        dashArray: crowdStyle.dashArray,
                        className: 'crowd-sourced-polygon flood-boundary-polygon'
                    });

                    if (showPolygons) crowdPoly.addTo(map);

                    crowdPoly.bindTooltip(`
                        <div class="zone-tooltip ${riskLevel.toLowerCase()}">
                            <i class="fa-solid fa-users"></i>
                            <span><strong>${crowdLabel.icon} ${point.title}</strong> · ${point.report_count} รายงาน</span>
                        </div>
                    `, { sticky: true, className: 'custom-leaflet-tooltip', direction: 'top' });

                    crowdPoly.bindPopup(popupContent);
                    mapPolygons.push(crowdPoly);

                } else {
                    // ─ Fallback: วงกลมรัศมีทั่วไป (ยังไม่ถึง threshold) ───────
                    const circle = L.circle([lat, lng], {
                        color: riskColor,
                        fillColor: riskColor,
                        fillOpacity: 0.18,
                        weight: 1.5,
                        radius: point.radius_meters || 200
                    }).addTo(map);
                    mapCircles.push(circle);
                }

                // 2. Custom HTML Pin
                const iconHtml = `
                    <div class="custom-flood-pin">
                        <div class="pin-outer ${riskLevel.toLowerCase()}">
                            <i class="fa-solid ${riskIcon}"></i>
                        </div>
                    </div>
                `;

                const customIcon = L.divIcon({
                    html: iconHtml,
                    className: 'flood-div-icon',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -18]
                });

                const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
                marker.bindPopup(popupContent);
                mapMarkers.push(marker);
            });
        }

        function updateStatistics() {
            const total = floodPoints.length;
            const critical = floodPoints.filter(p => p.risk_level === 'CRITICAL').length;
            const high = floodPoints.filter(p => p.risk_level === 'HIGH').length;

            document.getElementById('totalPointsCount').textContent = total;
            document.getElementById('criticalCount').textContent = `${critical} จุดวิกฤต`;
            document.getElementById('highCount').textContent = `${high} จุดเสี่ยงสูง`;
        }

        function filterMarkers(filterType, element) {
            currentFilter = filterType;
            document.querySelectorAll('.filter-chips .chip').forEach(c => c.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
            renderMarkers();
        }

        // Modal Controls
        function openReportModal() {
            // Auto-sync coordinates with current map center if not explicitly set
            const center = map.getCenter();
            syncCoordinates(center.lat, center.lng);

            // Auto-fill phone from localStorage
            const savedPhone = localStorage.getItem('roicore_phone');
            const phoneInput = document.getElementById('inputPhone');
            if (savedPhone && phoneInput && !phoneInput.value) {
                phoneInput.value = savedPhone;
            }

            document.getElementById('reportModal').classList.add('active');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.remove('active');
        }

        function handleBackdropClick(event) {
            if (event.target.id === 'reportModal') {
                closeReportModal();
            }
        }

        // Toast message
        function showToast(message, icon = 'fa-circle-check') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `<i class="fa-solid ${icon}" style="color: var(--accent-blue);"></i> <span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Handle Quick Report Submission (Fastest Flow)
        async function handleQuickReport(event) {
            event.preventDefault();
            const btn = document.getElementById('btnSubmitReport');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังส่งรายงาน...';

            const severityVal = parseInt(document.getElementById('inputSeverity').value, 10);
            const phoneVal = document.getElementById('inputPhone').value.trim();
            const latVal = parseFloat(document.getElementById('inputLat').value);
            const lngVal = parseFloat(document.getElementById('inputLng').value);

            // Remember phone number for subsequent fastest reports
            if (phoneVal) {
                localStorage.setItem('roicore_phone', phoneVal);
            }

            // Auto-generate crisp description from severity
            const defaultDesc = severityVal === 3
                ? 'น้ำท่วมระดับวิกฤต (60+ ซม.) รถเล็กผ่านไม่ได้'
                : (severityVal === 2
                    ? 'น้ำท่วมระดับปานกลาง (30-50 ซม.) ท่วมครึ่งล้อ'
                    : 'น้ำท่วมขังผิวทาง (10-20 ซม.) รถสัญจรได้');

            const payload = {
                latitude: latVal,
                longitude: lngVal,
                severity: severityVal,
                phone: phoneVal,
                description: defaultDesc,
                photos: currentAttachedPhoto ? [currentAttachedPhoto] : []
            };

            try {
                const res = await fetch('?api=reports', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                showToast('บันทึกรายงานสถานการณ์สำเร็จ (สถานะ: ออฟไลน์)', 'fa-circle-check');
            } catch (err) {
                showToast('บันทึกรายงานในเครื่องเรียบร้อยแล้ว (สถานะ: ออฟไลน์)', 'fa-wifi-slash');
            }

            // ─── Proximity Merge: รวมรายงานใกล้เคียงเข้าจุดเดิม ───────────────
            // ถ้าพิกัดที่รายงานอยู่ห่างจากจุดเดิม ≤ 500 เมตร ให้เพิ่ม report_count แทนสร้างจุดใหม่
            const MERGE_RADIUS_M = 500;
            const existingNearby = floodPoints.find(p =>
                haversineDistance(
                    p.location.latitude, p.location.longitude,
                    latVal, lngVal
                ) <= MERGE_RADIUS_M
            );

            if (existingNearby) {
                // Merge: เพิ่มจำนวนรายงานและอัปเดตระดับความเสี่ยงถ้า severity ใหม่สูงกว่า
                existingNearby.report_count = (existingNearby.report_count || 0) + 1;

                // ยกระดับความเสี่ยงถ้ารายงานใหม่รุนแรงกว่า
                const newRisk = payload.severity === 3 ? 'CRITICAL' : (payload.severity === 2 ? 'HIGH' : 'LOW');
                const riskRank = { 'LOW': 1, 'MEDIUM': 2, 'HIGH': 3, 'CRITICAL': 4 };
                if ((riskRank[newRisk] || 0) > (riskRank[existingNearby.risk_level] || 0)) {
                    existingNearby.risk_level = newRisk;
                    existingNearby.risk_level_label = newRisk === 'CRITICAL' ? 'ความเสี่ยงวิกฤต'
                        : (newRisk === 'HIGH' ? 'ความเสี่ยงสูง' : 'ความเสี่ยงต่ำ');
                }

                // อัปเดต radius เล็กน้อยตามรายงานสะสม
                if (!existingNearby.polygon_coordinates) {
                    existingNearby.radius_meters = Math.max(
                        existingNearby.radius_meters || 200,
                        payload.severity === 3 ? 250 : (payload.severity === 2 ? 180 : 120)
                    );
                }

                // เพิ่มรูปถ่ายใหม่ถ้ามี
                if (payload.photos.length > 0) {
                    existingNearby.photos = existingNearby.photos || [];
                    existingNearby.photos.push(...payload.photos);
                }

                // แจ้งเตือน user ว่า merge เรียบร้อย
                const cnt = existingNearby.report_count;
                if (cnt === 3) {
                    showToast('📍 รายงานครบ 3 ครั้ง — ระบบสร้างอาณาเขตน้ำท่วมอัตโนมัติแล้ว!', 'fa-draw-polygon');
                } else if (cnt === 5) {
                    showToast('⚠️ รายงานครบ 5 ครั้ง — ขยายอาณาเขตเฝ้าระวังชุมชน', 'fa-users');
                } else if (cnt === 10) {
                    showToast('🚨 รายงานครบ 10 ครั้ง — ยกระดับเป็นวิกฤต!', 'fa-triangle-exclamation');
                } else {
                    showToast(`บันทึกรายงานสำเร็จ (รวม ${cnt} รายงานในพื้นที่นี้)`, 'fa-circle-check');
                }

                // re-render เพื่อแสดงอาณาเขตที่อัปเดต
                renderMarkers();
                updateStatistics();
                map.flyTo([existingNearby.location.latitude, existingNearby.location.longitude], 13);

            } else {
                // ─── New Point: สร้างจุดใหม่ (ยังไม่มีจุดใกล้เคียง) ───────────
                const tempRisk = payload.severity === 3 ? 'CRITICAL' : (payload.severity === 2 ? 'HIGH' : 'LOW');
                const newPoint = {
                    id: 'point_' + Date.now(),
                    title: payload.description,
                    location: { latitude: payload.latitude, longitude: payload.longitude },
                    radius_meters: payload.severity === 3 ? 250 : (payload.severity === 2 ? 180 : 120),
                    water_level_meters: (payload.severity * 0.8).toFixed(2),
                    risk_level: tempRisk,
                    risk_level_label: tempRisk === 'CRITICAL' ? 'ความเสี่ยงวิกฤต' : (tempRisk === 'HIGH' ? 'ความเสี่ยงสูง' : 'ความเสี่ยงต่ำ'),
                    report_count: 1,
                    photos: payload.photos
                };

                floodPoints.unshift(newPoint);
                showToast('บันทึกรายงานสถานการณ์สำเร็จ', 'fa-circle-check');
                renderMarkers();
                updateStatistics();
                map.flyTo([payload.latitude, payload.longitude], 13);
            }

            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> ส่งรายงานน้ำท่วมทันที';
            removeSelectedPhoto();
            closeReportModal();
        }

        // Start Map on window load
        window.addEventListener('DOMContentLoaded', initMap);
    </script>
</body>
</html>

