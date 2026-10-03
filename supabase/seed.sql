-- =============================================================================
-- ROiCORE: Supabase Local Seed Data (Roi Et Region)
-- =============================================================================

-- Seed initial flood points
INSERT INTO public.flood_points (id, title, latitude, longitude, radius_meters, water_level_meters, risk_level, status, report_count, updated_at)
VALUES
    ('d3b07384-d113-4c91-9c62-124b89f53e01', 'สะพานข้ามแม่น้ำชี (ธวัชบุรี)', 16.0538, 103.6520, 250, 2.85, 'CRITICAL', 'ACTIVE', 5, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e02', 'จุดตัดคลองส่งน้ำเสลภูมิ', 16.0350, 103.7890, 180, 1.40, 'HIGH', 'ACTIVE', 3, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e03', 'ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก', 16.0680, 103.6850, 150, 0.45, 'MEDIUM', 'ACTIVE', 2, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e04', 'อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง', 16.0120, 103.7200, 300, 0.20, 'LOW', 'ACTIVE', 1, now())
ON CONFLICT (id) DO NOTHING;

-- Seed sample verified reports
INSERT INTO public.flood_reports (id, latitude, longitude, severity, description, phone, status, reported_at)
VALUES
    ('a1111111-1111-1111-1111-111111111111', 16.0538, 103.6520, 3, 'น้ำเอ่อล้นตลิ่งแม่น้ำชี เอ่อท่วมถนนสายหลักระดับ 40 เซนติเมตร รถเล็กผ่านไม่ได้', '0812345678', 'VERIFIED', now() - interval '2 hours'),
    ('a2222222-2222-2222-2222-222222222222', 16.0350, 103.7890, 2, 'มีน้ำท่วมขังรอการระบายบริเวณตลาดเสลภูมิ ระดับ 15 เซนติเมตร', '0898765432', 'PENDING', now() - interval '30 minutes')
ON CONFLICT (id) DO NOTHING;
