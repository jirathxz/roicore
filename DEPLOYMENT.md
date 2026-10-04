# คู่มือการ Deploy ระบบ ROiCORE ขึ้น Render และเชื่อมต่อ Supabase Cloud

คู่มือนี้สรุปขั้นตอนการนำระบบ **ROiCORE — Flood Intelligence Platform** ขึ้นสู่ระบบจริง (Production) โดยใช้ **Render** สำหรับเว็บแอปพลิเคชัน (PHP 8.3 + Apache + Docker) และ **Supabase Cloud** สำหรับฐานข้อมูล PostgreSQL พร้อม PostGIS

---

## ขั้นตอนที่ 1: การตั้งค่าฐานข้อมูลบน Supabase Cloud

1. เข้าสู่ระบบหรือสมัครใช้งานที่ [https://supabase.com](https://supabase.com)
2. สร้างโปรเจกต์ใหม่ (**New Project**):
   - **Name**: `roicore-prod`
   - **Database Password**: ตั้งรหัสผ่านที่ปลอดภัย
   - **Region**: แนะนำเลือก `Southeast Asia (Singapore)`
3. เมื่อโปรเจกต์พร้อมแล้ว ให้ไปที่เมนู **SQL Editor** ทางแถบซ้าย
4. เปิดไฟล์ [`supabase/cloud_setup.sql`](file:///c:/laragon/www/roicore/supabase/cloud_setup.sql) ในโปรเจกต์นี้ คัดลอกโค้ด SQL ทั้งหมดไปวางในช่อง SQL Editor แล้วกดปุ่ม **Run**
   - คำสั่งนี้จะทำการติดตั้ง PostGIS Extension, สร้างตาราง `profiles`, `flood_reports`, `flood_points`, `point_weather_snapshots`, ตั้งค่า Row Level Security (RLS) และใส่ข้อมูลตั้งต้น (Seed Data) ให้ทันที
5. ไปที่เมนู **Project Settings** (ไอคอนฟันเฟือง) &rarr; **API**:
   - คัดลอก **Project URL** (เช่น `https://xyzcompany.supabase.co`)
   - คัดลอก **Project API keys (anon / public)** (เช่น `eyJhbGciOi...`)

---

## ขั้นตอนที่ 2: การ Deploy เว็บแอปพลิเคชันบน Render

### วิธีที่ 1: Deploy ผ่าน Render Blueprint (แนะนำ - เร็วที่สุด)
1. Push โค้ดขึ้น GitHub Repository ของคุณ
2. เข้าสู่ระบบ [https://render.com](https://render.com)
3. กดปุ่ม **New +** &rarr; เลือก **Blueprint**
4. เชื่อมต่อกับ GitHub Repository `roicore`
5. Render จะตรวจพบไฟล์ [`render.yaml`](file:///c:/laragon/www/roicore/render.yaml) โดยอัตโนมัติ
6. กรอกค่า Environment Variables:
   - `SUPABASE_URL`: ใส่ Project URL จากขั้นตอนที่ 1
   - `SUPABASE_ANON_KEY`: ใส่ anon key จากขั้นตอนที่ 1
   - `OPENWEATHER_API_KEY`: (ใส่ถ้ามี หรือเว้นว่างเพื่อใช้ระบบจำลอง)
7. กด **Apply** เพื่อเริ่มการ Build และ Deploy อัตโนมัติ

---

### วิธีที่ 2: Deploy แบบ Manual Web Service
1. เข้าสู่ระบบ [https://render.com](https://render.com)
2. กดปุ่ม **New +** &rarr; เลือก **Web Service**
3. เลือก Repository `roicore`
4. ตั้งค่าดังนี้:
   - **Name**: `roicore`
   - **Region**: `Singapore (Southeast Asia)`
   - **Language / Runtime**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Instance Type**: `Free`
5. ไปที่หัวข้อ **Environment Variables** และเพิ่มตัวแปรต่อไปนี้:
   | Key | Value | คำอธิบาย |
   | :--- | :--- | :--- |
   | `APP_ENV` | `production` | โหมดการทำงาน |
   | `APP_DEBUG` | `false` | ปิด debug log บนหน้าเว็บ |
   | `DB_DRIVER` | `supabase` | ไดรเวอร์ฐานข้อมูล |
   | `SUPABASE_URL` | `https://your-project.supabase.co` | Project URL ของ Supabase |
   | `SUPABASE_ANON_KEY` | `eyJhbGci...` | Anon/Public Key ของ Supabase |
   | `OPENWEATHER_API_KEY` | *(ว่างไว้หรือใส่คีย์จริง)* | OpenWeather API |
6. กดปุ่ม **Create Web Service**

---

## ขั้นตอนที่ 3: ตรวจสอบการทำงานหลัง Deploy

เมื่อ Render ทำการ Deploy สำเร็จ จะได้รับ URL ของเว็บ เช่น `https://roicore.onrender.com`

- **หน้าแผนที่น้ำท่วม (Main Map)**: `https://roicore.onrender.com/`
- **หน้าศูนย์ควบคุมและทดสอบ API**: `https://roicore.onrender.com/api`
- **หน้าแดชบอร์ดศูนย์บัญชาการ 77 จังหวัด**: `https://roicore.onrender.com/dashboard`
- **หน้าสไลด์นำเสนอ Keynote**: `https://roicore.onrender.com/presentation`
- **ทดสอบ API จุดเฝ้าระวัง**: `https://roicore.onrender.com/api/points`
- **ทดสอบ API รายงานเหตุ**: `https://roicore.onrender.com/api/reports`
