-- =============================================================================
-- ROiCORE: Complete Supabase Cloud Setup Script
-- Run this script in the Supabase Cloud SQL Editor (https://app.supabase.com)
-- =============================================================================

-- 1. Enable PostGIS Extension
CREATE EXTENSION IF NOT EXISTS postgis;

-- 2. Custom Types & Enums
DO $$ BEGIN
    CREATE TYPE user_role AS ENUM ('victim', 'traveler', 'admin');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE report_status AS ENUM ('PENDING', 'VERIFIED', 'REJECTED', 'RESOLVED');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE risk_level AS ENUM ('LOW', 'MEDIUM', 'HIGH', 'CRITICAL');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

DO $$ BEGIN
    CREATE TYPE point_status AS ENUM ('ACTIVE', 'ESCALATING', 'CRITICAL', 'RESOLVED');
EXCEPTION
    WHEN duplicate_object THEN null;
END $$;

-- 3. Profiles Table
CREATE TABLE IF NOT EXISTS public.profiles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    phone TEXT UNIQUE NOT NULL,
    role user_role NOT NULL DEFAULT 'victim',
    display_name TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 4. Flood Reports Table
CREATE TABLE IF NOT EXISTS public.flood_reports (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    severity SMALLINT NOT NULL CHECK (severity BETWEEN 1 AND 3),
    description TEXT NOT NULL,
    phone TEXT,
    status report_status NOT NULL DEFAULT 'PENDING',
    source TEXT NOT NULL DEFAULT 'app',
    reported_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    verified_at TIMESTAMPTZ,
    reject_reason TEXT
);

CREATE INDEX IF NOT EXISTS idx_flood_reports_coords ON public.flood_reports (latitude, longitude);
CREATE INDEX IF NOT EXISTS idx_flood_reports_status ON public.flood_reports (status, reported_at DESC);

-- 5. Flood Points Table
CREATE TABLE IF NOT EXISTS public.flood_points (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title TEXT NOT NULL,
    latitude DOUBLE PRECISION NOT NULL,
    longitude DOUBLE PRECISION NOT NULL,
    radius_meters NUMERIC NOT NULL DEFAULT 200,
    water_level_meters NUMERIC NOT NULL DEFAULT 0.0,
    risk_level risk_level NOT NULL DEFAULT 'MEDIUM',
    status point_status NOT NULL DEFAULT 'ACTIVE',
    report_count INT NOT NULL DEFAULT 1,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_flood_points_coords ON public.flood_points (latitude, longitude);
CREATE INDEX IF NOT EXISTS idx_flood_points_status ON public.flood_points (status);

-- 6. Weather Snapshots Table
CREATE TABLE IF NOT EXISTS public.point_weather_snapshots (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    point_id UUID REFERENCES public.flood_points(id) ON DELETE CASCADE,
    rainfall_1h NUMERIC,
    rainfall_24h NUMERIC,
    temp_c NUMERIC,
    condition TEXT,
    fetched_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 7. Row Level Security (RLS)
ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.flood_reports ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.flood_points ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.point_weather_snapshots ENABLE ROW LEVEL SECURITY;

-- Allow public anonymous reads & writes for flood reports & points
DROP POLICY IF EXISTS "Public read flood_reports" ON public.flood_reports;
CREATE POLICY "Public read flood_reports" ON public.flood_reports FOR SELECT USING (true);

DROP POLICY IF EXISTS "Public insert flood_reports" ON public.flood_reports;
CREATE POLICY "Public insert flood_reports" ON public.flood_reports FOR INSERT WITH CHECK (true);

DROP POLICY IF EXISTS "Public update flood_reports" ON public.flood_reports;
CREATE POLICY "Public update flood_reports" ON public.flood_reports FOR UPDATE USING (true);

DROP POLICY IF EXISTS "Public read flood_points" ON public.flood_points;
CREATE POLICY "Public read flood_points" ON public.flood_points FOR SELECT USING (true);

DROP POLICY IF EXISTS "Public write flood_points" ON public.flood_points;
CREATE POLICY "Public write flood_points" ON public.flood_points FOR ALL USING (true);

DROP POLICY IF EXISTS "Public read weather" ON public.point_weather_snapshots;
CREATE POLICY "Public read weather" ON public.point_weather_snapshots FOR SELECT USING (true);

DROP POLICY IF EXISTS "Public write weather" ON public.point_weather_snapshots;
CREATE POLICY "Public write weather" ON public.point_weather_snapshots FOR ALL USING (true);

-- 8. Realtime Publication
DO $$ BEGIN
    ALTER PUBLICATION supabase_realtime ADD TABLE public.flood_reports, public.flood_points;
EXCEPTION
    WHEN others THEN null;
END $$;

-- 9. Initial Seed Data
INSERT INTO public.flood_points (id, title, latitude, longitude, radius_meters, water_level_meters, risk_level, status, report_count, updated_at)
VALUES
    ('d3b07384-d113-4c91-9c62-124b89f53e01', 'สะพานข้ามแม่น้ำชี (ธวัชบุรี)', 16.0538, 103.6520, 250, 2.85, 'CRITICAL', 'ACTIVE', 5, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e02', 'จุดตัดคลองส่งน้ำเสลภูมิ', 16.0350, 103.7890, 180, 1.40, 'HIGH', 'ACTIVE', 3, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e03', 'ถนนสายเลี่ยงเมืองร้อยเอ็ด ทิศตะวันออก', 16.0680, 103.6850, 150, 0.45, 'MEDIUM', 'ACTIVE', 2, now()),
    ('d3b07384-d113-4c91-9c62-124b89f53e04', 'อ่างเก็บน้ำธวัชชัย ระดับเฝ้าระวัง', 16.0120, 103.7200, 300, 0.20, 'LOW', 'ACTIVE', 1, now())
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.flood_reports (id, latitude, longitude, severity, description, phone, status, reported_at)
VALUES
    ('a1111111-1111-1111-1111-111111111111', 16.0538, 103.6520, 3, 'น้ำเอ่อล้นตลิ่งแม่น้ำชี เอ่อท่วมถนนสายหลักระดับ 40 เซนติเมตร รถเล็กผ่านไม่ได้', '0812345678', 'VERIFIED', now() - interval '2 hours'),
    ('a2222222-2222-2222-2222-222222222222', 16.0350, 103.7890, 2, 'มีน้ำท่วมขังรอการระบายบริเวณตลาดเสลภูมิ ระดับ 15 เซนติเมตร', '0898765432', 'PENDING', now() - interval '30 minutes')
ON CONFLICT (id) DO NOTHING;
