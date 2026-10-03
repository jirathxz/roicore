-- =============================================================================
-- ROiCORE: Supabase Local Initial Schema Migration
-- =============================================================================

-- Enable PostGIS extension for geo calculations
CREATE EXTENSION IF NOT EXISTS postgis;

-- 1. Custom Types & Enums
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

-- 2. Profiles Table (Citizen / Reporter / Admin)
CREATE TABLE IF NOT EXISTS public.profiles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    phone TEXT UNIQUE NOT NULL,
    role user_role NOT NULL DEFAULT 'victim',
    display_name TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 3. Flood Reports Table (Citizen submissions)
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

-- 4. Flood Points Table (Monitored flood areas & gauges)
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

-- 5. Weather Snapshots Table
CREATE TABLE IF NOT EXISTS public.point_weather_snapshots (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    point_id UUID REFERENCES public.flood_points(id) ON DELETE CASCADE,
    rainfall_1h NUMERIC,
    rainfall_24h NUMERIC,
    temp_c NUMERIC,
    condition TEXT,
    fetched_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- 6. Row Level Security (RLS)
ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.flood_reports ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.flood_points ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.point_weather_snapshots ENABLE ROW LEVEL SECURITY;

-- Allow public anonymous reads & inserts for flood reports & points in local/app mode
CREATE POLICY "Public read flood_reports" ON public.flood_reports FOR SELECT USING (true);
CREATE POLICY "Public insert flood_reports" ON public.flood_reports FOR INSERT WITH CHECK (true);
CREATE POLICY "Public update flood_reports" ON public.flood_reports FOR UPDATE USING (true);

CREATE POLICY "Public read flood_points" ON public.flood_points FOR SELECT USING (true);
CREATE POLICY "Public write flood_points" ON public.flood_points FOR ALL USING (true);

CREATE POLICY "Public read weather" ON public.point_weather_snapshots FOR SELECT USING (true);
CREATE POLICY "Public write weather" ON public.point_weather_snapshots FOR ALL USING (true);

-- 7. Realtime Publication
DO $$ BEGIN
    ALTER PUBLICATION supabase_realtime ADD TABLE public.flood_reports, public.flood_points;
EXCEPTION
    WHEN others THEN null;
END $$;
