# ROiCORE — Front-End Prompting Master Guide with Antigravity IDE

> **RO<span style="color:#2563EB;font-weight:800;font-style:normal">i</span>CORE** — Flood Intelligence Platform  
> คู่มือและแบบแผนการเขียนคำสั่ง (Prompt Engineering Playbook) ฝั่ง **Front-End** เพื่อสร้างเว็บแอปพลิเคชันที่มีงานออกแบบระดับพรีเมียม กะทัดรัด ไร้ขอบ ไร้เงา (White Modern UI) และตอบโจทย์ผู้ใช้งานจริงด้วย **Google Antigravity IDE**

---

## สารบัญ (Table of Contents)

1. [บทนำ: ทำไมต้องมีวิธี Prompt เฉพาะสำหรับ Front-End?](#1-บทนำ-ทำไมต้องมีวิธี-prompt-เฉพาะสำหรับ-front-end)
2. [5 กฎเหล็กในการสั่งงาน Antigravity IDE ฝั่ง Front-End](#2-5-กฎเหล็กในการสั่งงาน-antigravity-ide-ฝั่ง-front-end)
3. [โครงสร้าง Prompt Framework สำหรับสร้าง UI ชั้นสูง](#3-โครงสร้าง-prompt-framework-สำหรับสร้าง-ui-ชั้นสูง)
4. [รวมชุด Master Prompts ตามพิมพ์เขียว ROiCORE](#4-รวมชุด-master-prompts-ตามพิมพ์เขียว-roicore)
   - [4.1 Prompt สำหรับวาง Design System (White Modern Tokens)](#41-prompt-สำหรับวาง-design-system-white-modern-tokens)
   - [4.2 Prompt สำหรับสร้าง Interactive Map + GISTDA + Dynamic Polygon](#42-prompt-สำหรับสร้าง-interactive-map--gistda--dynamic-polygon)
   - [4.3 Prompt สำหรับสร้าง Quick Report Modal (≤ 3 Taps Flow)](#43-prompt-สำหรับสร้าง-quick-report-modal--3-taps-flow)
   - [4.4 Prompt สำหรับสร้าง Flood Intelligence Dashboard](#44-prompt-สำหรับสร้าง-flood-intelligence-dashboard)
   - [4.5 Prompt สำหรับเก็บงานละเอียด (Padding, Gap, Radius, Visual Hierarchy)](#45-prompt-สำหรับเก็บงานละเอียด-padding-gap-radius-visual-hierarchy)
5. [เทคนิคขั้นสูง (Power Tips) ในการใช้ Antigravity IDE](#5-เทคนิคขั้นสูง-power-tips-ในการใช้-antigravity-ide)
6. [Checklist ตรวจสอบคุณภาพ Front-End ก่อนส่งมอบงาน](#6-checklist-ตรวจสอบคุณภาพ-front-end-ก่อนส่งมอบงาน)
7. [การติดตั้งและเปิดใช้งานโปรเจกต์ (Local Run)](#7-การติดตั้งและเปิดใช้งานโปรเจกต์-local-run)

---

## 1. บทนำ: ทำไมต้องมีวิธี Prompt เฉพาะสำหรับ Front-End?

การใช้ AI เขียนโค้ด Front-End แบบทั่วไป (Generic Prompting) มักจะเจอปัญหาซ้ำซาก:
* ❌ ได้ดีไซน์แบบ **"AI Slop"** (ม่วง-ฟ้าเกรเดียนท์ลอยๆ ขอบดำหนา เงาเบลอๆ ซ้อนกันมั่ว)
* ❌ สเกล Spacing เทอะทะ ปุ่มใหญ่เกินจำเป็น เปลืองพื้นที่หน้าจอ (Low Information Density)
* ❌ มี Badge / Chip พร่ำเพรื่อ ไร้ประโยชน์ต่อการตัดสินใจของผู้ใช้
* ❌ ภาษาที่แสดงเป็นศัพท์เทคนิคแปลกๆ มีวงเล็บภาษาอังกฤษปะปน

**ROiCORE Front-End Playbook** นี้คือแนวทางที่พิสูจน์แล้วในการควบคุม **Antigravity IDE** ให้สร้างส่วนติดต่อผู้ใช้ที่:
1. **White Modern (No Border, No Shadow)**: สะอาดตา ใช้ความต่างของพื้นผิว (Surface Layering) แทนเงาและเส้นขอบ
2. **Compact & High-Density**: จัดวางข้อมูลได้แน่น กระชับ ชัดเจน
3. **Fastest UX**: ผู้ประสบภัยส่งรายงานได้ใน **≤ 3 Taps**
4. **Spatial Intelligence**: วาดขอบเขตพื้นที่น้ำท่วมอัตโนมัติ (Dynamic Polygon Zones) และเชื่อมต่อดาวเทียม GISTDA 77 จังหวัด

---

## 2. 5 กฎเหล็กในการสั่งงาน Antigravity IDE ฝั่ง Front-End

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    5 GOLDEN RULES FOR FRONT-END PROMPTS                 │
├─────────────────────────────────────────────────────────────────────────┤
│ 1. Token-First        | กำหนด CSS Variables (Color, Radius, Gap) ล่วงหน้า│
│ 2. Strict Negative    | สั่งห้ามสิ่งที่ห้ามมีให้เด็ดขาด (No Border, No Shadow) │
│ 3. Density & Spacing  | บังคับใช้ Compact Spacing (6px - 16px)           │
│ 4. Flow-Constrained   | กำหนดจำนวน Tap/Click สูงสุดในแต่ละ Use Case     │
│ 5. Thai-First Human UX| สั่งให้ใช้ภาษาไทยคนใช้จริง ห้ามใช้ศัพท์เทคนิค   │
└─────────────────────────────────────────────────────────────────────────┘
```

1. **Rule 1: Token-First Definition**  
   อย่าปล่อยให้ AI สุ่มสีหรือค่า Padding เอง ต้องให้ AI ยึดตาม Design Tokens เช่น `--bg-body: #F1F5F9`, `--surface: #FFFFFF`, `--r-xl: 20px` เสมอ
2. **Rule 2: Strict Negative Constraints**  
   ใส่ข้อห้ามเชิงลบให้ชัดเจน เช่น:  
   `"ห้ามใช้ border ทุกชนิด, ห้ามใช้ box-shadow หรือ drop-shadow เด็ดขาด, ห้ามใช้ badge สิ้นเปลือง"`
3. **Rule 3: Radius Scale Hierarchy**  
   ตั้งกฎโครงสร้างรัศมีความโค้ง: **องค์ประกอบชั้นนอกต้องโค้งมากกว่าชั้นในเสมอ** (`Card 20px -> Row 12px -> Tag 8px`)
4. **Rule 4: UX Flow Speed Limit**  
   ระบุขีดจำกัดของขั้นตอนการทำงาน เช่น: `"ผู้ใช้ต้องกดส่งรายงานน้ำท่วมเสร็จสิ้นได้ภายในไม่เกิน 3 ครั้ง (≤ 3 Taps) พร้อมดึงพิกัด GPS อัตโนมัติ"`
5. **Rule 5: Human-Centric Localized Thai**  
   ระบุบริบทภาษา: `"ใช้ภาษาไทยที่กระชับ สุภาพ เข้าใจง่าย ห้ามมีศัพท์เชิงเทคนิคบนหน้าจอ และห้ามมีวงเล็บภาษาอังกฤษ"`

---

## 3. โครงสร้าง Prompt Framework สำหรับสร้าง UI ชั้นสูง

สูตรโครงสร้าง Prompt (The **C-P-R-O-S** Framework) ที่ให้ผลลัพธ์แม่นยำสูงสุดใน Antigravity IDE:

| ส่วนประกอบ | รายละเอียด | ตัวอย่างใน ROiCORE |
|---|---|---|
| **C - Context (บริบท)** | ระบุบทบาทของ AI และประเภทของระบบ | *"คุณเป็น Senior Front-End Design Engineer พัฒนาระบบ ROiCORE"* |
| **P - Purpose (เป้าหมาย)** | ฟังก์ชันหรือหน้าจอที่ต้องการสร้าง | *"สร้างหน้าหลัก OpenStreetMap สำหรับเฝ้าระวังน้ำท่วมและรายงานเหตุ"* |
| **R - Rules & Tokens (กฎและโทเคน)** | Design System และข้อจำกัดห้ามละเมิด | *"Theme: White Modern (No Border, No Shadow), Surface Layering"* |
| **O - Operation Flow (ขั้นตอนผู้ใช้)** | ลำดับการกดและการมีปฏิสัมพันธ์ของผู้ใช้ | *"กดปุ่มแจ้งเหตุ -> เปิด Bottom Sheet -> เลือก 1 ใน 4 ระดับน้ำ -> กดส่ง"* |
| **S - Spatial / Tech Stack (เครื่องมือ)** | ไลบรารีและเทคโนโลยีที่ต้องใช้ | *"Leaflet 1.9.4, OpenStreetMap, Font Awesome 6, Noto Sans Thai"* |

---

## 4. รวมชุด Master Prompts ตามพิมพ์เขียว ROiCORE

สามารถคัดลอก Prompt ด้านล่างนี้ไปใช้งานใน Antigravity IDE ได้ทันที:

### 4.1 Prompt สำหรับวาง Design System (White Modern Tokens)

```text
ทำหน้าที่เป็น Senior Front-End Engineer ออกแบบ CSS Design System สำหรับเว็บ ROiCORE ในธีม "White Modern UI":

ข้อกำหนดและกฎเหล็กการออกแบบ:
1. ห้ามใช้เส้นขอบ (border / outline) ทุกชนิดในทุกคอมโพเนนต์
2. ห้ามใช้เงา (box-shadow / drop-shadow) เด็ดขาด มิติความลึกต้องเกิดจาก Surface Layering เท่านั้น:
   - --bg-body: #F1F5F9 (Slate 100 พื้นหลังจอ)
   - --surface: #FFFFFF (การ์ดหลัก)
   - --surface-subtle: #F8FAFC (แถวย่อย / ช่องกรอก)
   - --surface-active: #E2E8F0 (สถานะที่ถูกเลือก)
3. ระบบรัศมีความโค้ง (Radius Hierarchy):
   - --r-sm: 8px (ป้ายย่อย)
   - --r-md: 10px (ปุ่ม, ช่อง input)
   - --r-lg: 14px (การ์ดย่อย)
   - --r-xl: 20px (การ์ดหลัก, Sheet, Modal)
   - --r-full: 9999px (Pill, Avatar)
   องค์ประกอบด้านนอกต้องมีรัศมีโค้งมากกว่าด้านในเสมอ
4. สี Accent: Modern Blue (#2563EB), โลโก้ ROiCORE (ตัว i สีน้ำเงิน ตั้งตรง)
5. Typography: 'Noto Sans Thai' และ 'Inter' ขนาดกระชับ สบายตา
```

---

### 4.2 Prompt สำหรับสร้าง Interactive Map + GISTDA + Dynamic Polygon

```text
สร้างหน้าจอ Interactive Flood Map เต็มหน้าจอ (100vw, 100vh) โดยใช้ Leaflet.js และ OpenStreetMap:

ความสามารถที่ต้องการ:
1. Navbar ด้านบนแบบลอยตัว (Floating Navbar) ไม่มีเส้นขอบ ไม่มีเงา พร้อมปุ่มสลับไปหน้า "แดชบอร์ด" และ "API Console"
2. แสดงหมุดจุดน้ำท่วม (Custom Blue/Red Markers) แยกตามระดับความรุนแรง (วิกฤต, สูง, ปานกลาง, เฝ้าระวัง)
3. เมื่อคลิกหมุดให้เปิด Card สรุปสถานะ: ระดับน้ำ, ปริมาณฝน, และรูปถ่ายสถานที่
4. ผสานข้อมูลดาวเทียมจาก GISTDA (https://disaster.gistda.or.th/services/open-api):
   - มีปุ่มสลับเปิด-ปิดเลเยอร์น้ำท่วมจากดาวเทียมครอบคลุม 77 จังหวัด
5. ระบบวาดอาณาเขตอัตโนมัติ (Dynamic Flood Boundary Zones):
   - บริเวณที่มีรายงานน้ำท่วมซ้ำซ้อนหรือระดับวิกฤต ให้วาดเป็นรูปหลายเหลี่ยม (Semi-transparent Polygon) แสดงขอบเขตพื้นที่เสี่ยงภัย
6. มีปุ่มกด Floating Action Button (FAB) สีน้ำเงินมุมขวาล่างสำหรับ "แจ้งน้ำท่วมด่วน"
```

---

### 4.3 Prompt สำหรับสร้าง Quick Report Modal (≤ 3 Taps Flow)

```text
สร้าง Modal/Bottom Sheet สำหรับแจ้งเหตุน้ำท่วมด่วน เน้นหลักการ User-Friendly, Compact และ Fastest (เสร็จสิ้นใน ≤ 3 Taps):

รายละเอียดฟอร์ม:
1. ช่องที่ 1: เลือกระดับน้ำ (4 ปุ่มกดแบบ Grid ไร้เส้นขอบ):
   - "เล็กน้อย" / "ปานกลาง" / "รถเล็กผ่านไม่ได้" / "วิกฤตจมมิด"
2. ช่องที่ 2: เบอร์โทรศัพท์ติดต่อ (บันทึกลง LocalStorage อัตโนมัติ เพื่อไม่ต้องพิมพ์ซ้ำในครั้งต่อไป)
3. ช่องที่ 3: ปุ่มถ่ายภาพ/เลือกรูปภาพ (Compact File Input พร้อม Preview รูปขนาดย่อม)
4. พิกัดตำแหน่ง: ดึง Geolocation GPS อัตโนมัติทันทีที่เปิดฟอร์ม พร้อมแสดงชื่อสถานที่ปัจจุบัน
5. ปุ่มส่งรายงาน: สีน้ำเงินเต็มความกว้าง แสดงแอนิเมชันสถานะกำลังส่ง
6. การใช้ภาษา: ภาษาไทยล้วน ห้ามมีศัพท์เทคนิค ห้ามมีวงเล็บภาษาอังกฤษ
```

---

### 4.4 Prompt สำหรับสร้าง Flood Intelligence Dashboard

```text
สร้างหน้า แดชบอร์ดติดตามสถานการณ์น้ำท่วม (/dashboard) ให้ตรวจสอบข้อมูลได้ทุกพื้นที่ในประเทศไทย:

องค์ประกอบที่ต้องมี:
1. แถบสถิติสรุปภาพรวม (KPI Stat Cards):
   - จำนวนจังหวัดที่ประสบภัย
   - จุดวิกฤตสะสม
   - รายงานวันนี้จากประชาชน
   - ดัชนีความเสี่ยงฝนตกสะสม
2. แถบค้นหาและตัวกรอง (Filter Toolbar):
   - Dropdown เลือกจังหวัด (77 จังหวัดทั่วไทย)
   - Filter ระดับความรุนแรง
   - Toggle กรองตามแหล่งที่มา (ข้อมูลดาวเทียม GISTDA / รายงานจากประชาชน)
3. รายการจุดรายงาน (Incident List):
   - แสดงแบบตารางและ Card ที่มีระยะห่าง (Gap/Padding) พอดี
   - คลิกเพื่อเปิด Drawer รายละเอียด: รูปภาพใหญ่, แผนที่ย่อ, พิกัดละติจูด-ลองจิจูด และประวัติการเปลี่ยนแปลง
4. ปฏิบัติตามธีม White Modern: ไม่มีเงา ไม่มีเส้นขอบ ใช้สีพื้นหลังและ Surface Contrast แยกสัดส่วน
```

---

### 4.5 Prompt สำหรับเก็บงานละเอียด (Padding, Gap, Radius, Visual Hierarchy)

```text
ช่วยตรวจสอบและ Refactor งาน Front-End ของหน้าเว็บทั้งหมดตามเกณฑ์ Pixel-Perfect Polish:

รายการที่ต้องตรวจและแก้ไข:
1. Spacing Audit: ตรวจสอบ padding, gap, margin ทุกจุดให้อยู่ใน Scale: 4px, 8px, 12px, 16px, 24px ห้ามมีเศษ pixel แปลกปลอม
2. Radius Hierarchy: ตรวจสอบว่า Container ด้านนอกใช้ --r-xl (20px), ด้านในใช้ --r-lg (14px) หรือ --r-md (10px), ชิ้นส่วนเล็กใช้ --r-sm (8px)
3. Shadow & Border Elimination: ค้นหาและลบ `border: ...` และ `box-shadow: ...` หรือ `drop-shadow` ที่อาจหลงเหลืออยู่ออกทั้งหมด
4. Clean Typography: ตรวจสอบน้ำหนักตัวอักษร หัวข้อใช้ Bold (700), เนื้อหาใช้ Regular (400) / Medium (500), ข้อความอธิบายใช้ Slate 500 (#64748B)
5. Micro-interactions: เพิ่ม hover effect ด้วยการเปลี่ยนสีพื้นผิวแบบนุ่มนวล (transition: background 0.15s ease)
```

---

## 5. เทคนิคขั้นสูง (Power Tips) ในการใช้ Antigravity IDE

### 5.1 การเรียกใช้ Skills เฉพาะทาง
คุณสามารถระบุ Skill ใน Prompt เพื่อให้ Antigravity ใช้กระบวนการคิดที่ลึกซึ้งยิ่งขึ้น:
* `/responsive-design` — เมื่อต้องการวาง Grid และ Breakpoint สำหรับ Mobile, Tablet, Desktop
* `anti-slop-design` — ป้องกันดีไซน์สำเร็จรูป บังคับให้สร้างงานออกแบบเฉพาะตัวที่ดูพรีเมียม
* `design-taste-frontend` — ปรับแต่งคู่สี การเว้นจังหวะ และฟอนต์ให้เข้ากับบริบทงาน
* `emil-design-eng` — เพิ่มความประณีตในการทำ Transition และการโต้ตอบของผู้ใช้

### 5.2 การอ้างอิงไฟล์บริบท (Context Tagging)
เมื่อต้องการแก้ไข UI ให้ระบุชื่อไฟล์ที่เกี่ยวข้องเสมอ เช่น:
> *"ช่วยปรับแต่งปุ่มใน `public/map_view.php` และเพิ่มตัวกรองใน `public/dashboard_view.php` ให้ใช้ Design Tokens เดียวกันกับ `PLAN.md`"*

---

## 6. Checklist ตรวจสอบคุณภาพ Front-End ก่อนส่งมอบงาน

- [x] **No Border**: ไม่มี `border` หรือเส้นขอบตีกรอบแม้แต่จุดเดียว
- [x] **No Shadow**: ไม่มี `box-shadow` หรือ `drop-shadow`
- [x] **Surface Layering**: พื้นผิวแยกชั้นชัดเจน (`#F1F5F9` -> `#FFFFFF` -> `#F8FAFC` -> `#E2E8F0`)
- [x] **Radius Scale**: องค์ประกอบชั้นนอกมนกว่าชั้นในเสมอ
- [x] **Fast Action**: รายงานน้ำท่วมทำได้ภายใน ≤ 3 ครั้ง
- [x] **Thai First**: ภาษาไทยล้วน ไม่มีศัพท์เทคนิค ไม่มีวงเล็บภาษาอังกฤษ
- [x] **Responsive**: ใช้งานได้อย่างสมบูรณ์แบบทั้งบนมือถือและจอคอมพิวเตอร์
- [x] **Spatial Intelligence**: หมุด, เลเยอร์ GISTDA 77 จังหวัด และ Polygon แสดงผลถูกต้อง

---

## 7. การติดตั้งและเปิดใช้งานโปรเจกต์ (Local Run)

### 1. ติดตั้ง Dependencies
```bash
composer install
```

### 2. ตั้งค่าไฟล์ Environment
```bash
cp .env.example .env
```

### 3. เปิดเว็บเซิร์ฟเวอร์
```bash
php -S localhost:8000 -t public
```

### 4. เส้นทางการใช้งาน
* 🗺️ **หน้าแผนที่น้ำท่วม (Main Map)**: `http://localhost:8000/` หรือ `http://localhost:8000/map`
* 📊 **หน้าแดชบอร์ดสรุปสถานการณ์ (Dashboard)**: `http://localhost:8000/dashboard`
* ⚡ **หน้าคอนโซลนักพัฒนา (API Console)**: `http://localhost:8000/api`

---

> **ROiCORE** — Designed with Precision, Built with Clean Architecture on Antigravity IDE.
