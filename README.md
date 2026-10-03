# ROiCORE — คู่มือเริ่มต้นและแบบแผนการพัฒนา Front-End ด้วย Google Antigravity IDE

> **RO<span style="color:#2563EB;font-weight:800;font-style:normal">i</span>CORE** — Flood Intelligence Platform  
> เอกสารคู่มือฉบับสมบูรณ์สำหรับทีมนักพัฒนา Front-End: ตั้งแต่การ **Clone Git** ➔ **เปิดโปรเจกต์ใน XAMPP / htdocs** ➔ **การใช้งาน Terminal** ➔ **เทคนิคการเขียน Prompt สั่งการ Antigravity IDE** ➔ **การ Push/Pull Git**

---

## 📑 สารบัญ (Table of Contents)

1. [ภาพรวมระบบและปรัชญาการออกแบบ](#1-ภาพรวมระบบและปรัชญาการออกแบบ)
2. [ขั้นตอนที่ 1: การติดตั้งและโคลนโปรเจกต์ (Git Clone ใน XAMPP)](#2-ขั้นตอนที่-1-การติดตั้งและโคลนโปรเจกต์-git-clone-ใน-xampp)
3. [ขั้นตอนที่ 2: การเปิดโฟลเดอร์ใน Google Antigravity IDE](#3-ขั้นตอนที่-2-การเปิดโฟลเดอร์ใน-google-antigravity-ide)
4. [ขั้นตอนที่ 3: การเปิดและใช้งาน Terminal ภายใน IDE](#4-ขั้นตอนที่-3-การเปิดและใช้งาน-terminal-ภายใน-ide)
5. [ขั้นตอนที่ 4: การสั่งงาน AI ด้วย Prompt (Prompting Engineering Mastery)](#5-ขั้นตอนที่-4-การสั่งงาน-ai-ด้วย-prompt-prompting-engineering-mastery)
   - [5.1 5 กฎเหล็กในการสั่งงาน Front-End](#51-5-กฎเหล็กในการสั่งงาน-front-end)
   - [5.2 การอ้างอิงไฟล์บริบทด้วยสัญลักษณ์ `@` (@rules.md, @design.md)](#52-การอ้างอิงไฟล์บริบทด้วยสัญลักษณ์--rulesmd-designmd)
   - [5.3 รวม Master Prompts ตามพิมพ์เขียว ROiCORE](#53-รวม-master-prompts-ตามพิมพ์เขียว-roicore)
6. [ขั้นตอนที่ 5: วงจรการส่งงานด้วย Git (Pull, Add, Commit, Push)](#6-ขั้นตอนที่-5-วงจรการส่งงานด้วย-git-pull-add-commit-push)
7. [การตรวจสอบและเช็กลิสต์คุณภาพ (Quality Checklist)](#7-การตรวจสอบและเช็กลิสต์คุณภาพ-quality-checklist)
8. [ตารางสรุปคำสั่งลัดและคีย์บอร์ด (Cheat Sheet)](#8-ตารางสรุปคำสั่งลัดและคีย์บอร์ด-cheat-sheet)

---

## 1. ภาพรวมระบบและปรัชญาการออกแบบ

**ROiCORE** คือแพลตฟอร์มศูนย์กลางข้อมูลและเฝ้าระวังภัยน้ำท่วมอัจฉริยะ ออกแบบภายใต้มาตรฐาน:
* **White Modern UI (No Border, No Shadow)**: สะอาดตา ไร้เส้นขอบ ไร้เงา สร้างมิติด้วยระดับชั้นของสีพื้นผิว (Surface Layering)
* **Compact & High-Density UI**: สัดส่วนกระชับ วางข้อมูลได้แน่นและอ่านง่าย ไม่เทอะทะ
* **Fastest & Frictionless UX**: ผู้ประสบภัยส่งรายงานน้ำท่วมด่วนได้ภายใน **≤ 3 Taps**
* **Spatial Intelligence**: แผนที่ OpenStreetMap + ข้อมูลดาวเทียม GISTDA 77 จังหวัด + วาดอาณาเขตพื้นที่ท่วมอัตโนมัติ (Dynamic Flood Polygon Zones)

---

## 2. ขั้นตอนที่ 1: การติดตั้งและโคลนโปรเจกต์ (Git Clone ใน XAMPP)

การ **Clone** คือการคัดลอกไฟล์โปรเจกต์ทั้งหมดจาก GitHub ลงมาไว้ในเครื่องคอมพิวเตอร์ของคุณเป็นครั้งแรก

### 2.1 เปิด Terminal ไปยังโฟลเดอร์เว็บเซิร์ฟเวอร์
เปิดโปรแกรม **Terminal / PowerShell / Git Bash** ในเครื่องคอมพิวเตอร์ แล้วพิมพ์:

```bash
# สำหรับผู้ใช้ XAMPP
cd C:\xampp\htdocs

# หรือสำหรับผู้ใช้ Laragon
cd C:\laragon\www
```

### 2.2 รันคำสั่ง Git Clone
```bash
git clone https://github.com/jirathxz/roicore.git
```

เมื่อดาวน์โหลดเสร็จสมบูรณ์ คุณจะได้โฟลเดอร์โปรเจกต์อยู่ที่ `C:\xampp\htdocs\roicore`

---

## 3. ขั้นตอนที่ 2: การเปิดโฟลเดอร์ใน Google Antigravity IDE

> ⚠️ **ข้อควรระวัง**: ต้องเปิดเป็น **"โฟลเดอร์หลักของโปรเจกต์ (Open Folder)"** เสมอ ห้ามเปิดเฉพาะไฟล์เดี่ยว เพื่อให้ AI มองเห็นบริบทไฟล์ทั้งระบบ

### วิธีเปิดโฟลเดอร์:
1. เปิดโปรแกรม **Google Antigravity IDE**
2. ไปที่เมนูด้านบนซ้าย: คลิก **File** ➔ **Open Folder...** (หรือกดคีย์ลัด `Ctrl + K` แล้วกด `Ctrl + O`)
3. เลือกไปยังโฟลเดอร์: `C:\xampp\htdocs\roicore` (หรือ `C:\laragon\www\roicore`) แล้วคลิก **Select Folder**

---

## 4. ขั้นตอนที่ 3: การเปิดและใช้งาน Terminal ภายใน IDE

Antigravity IDE มี Terminal ในตัว ช่วยให้ควบคุมเซิร์ฟเวอร์และรันคำสั่งได้โดยไม่ต้องสลับโปรแกรม:

```
┌──────────────────────────────────────────────────────────────┐
│  Antigravity IDE Editor Window                               │
│  ┌────────────────────────────────────────────────────────┐  │
│  │ Code Editor (HTML / PHP / JS)                          │  │
│  └────────────────────────────────────────────────────────┘  │
│  ┌────────────────────────────────────────────────────────┐  │
│  │ Integrated Terminal (กด Ctrl + ~ เพื่อเปิด/ปิด)        │  │
│  │ PS C:\xampp\htdocs\roicore> php -S localhost:8000 ...  │  │
│  └────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
```

### 4.1 เปิดหน้าต่าง Terminal
* กดคีย์ลัด: **`Ctrl + ~`** (ปุ่ม Grave Accent/หนอน ด้านบนปุ่ม Tab)
* หรือไปที่เมนู: **Terminal** ➔ **New Terminal**

### 4.2 ติดตั้ง Dependencies และตั้งค่าระบบ (.env)
พิมพ์คำสั่งต่อไปนี้ใน Terminal:
```bash
# 1. ติดตั้งชุดแพ็กเกจ PHP
composer install

# 2. คัดลอกไฟล์ Environment
cp .env.example .env
```

### 4.3 เปิดเว็บเซิร์ฟเวอร์ทดสอบ (Local Server)
```bash
php -S localhost:8000 -t public
```
> เปิดเบราว์เซอร์เข้าใช้งานที่: `http://localhost:8000/`
> * 🗺️ **หน้าแผนที่น้ำท่วม (Main Map)**: `http://localhost:8000/` หรือ `http://localhost:8000/map`
> * 📊 **หน้าแดชบอร์ดสรุปสถานการณ์ (Dashboard)**: `http://localhost:8000/dashboard`
> * ⚡ **หน้าคอนโซลนักพัฒนา (API Console)**: `http://localhost:8000/api`

---

## 5. ขั้นตอนที่ 4: การสั่งงาน AI ด้วย Prompt (Prompting Engineering Mastery)

หน้าต่าง AI Chat ของ Antigravity IDE (แถบด้านขวา) ทำหน้าที่เป็นคู่หูเขียนโค้ด (Pair Programmer) ของคุณ

---

### 5.1 5 กฎเหล็กในการสั่งงาน Front-End

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

1. **Rule 1: Token-First**: กำหนด CSS Variables เช่น `--bg-body: #F1F5F9`, `--surface: #FFFFFF`, `--r-xl: 20px` เสมอ
2. **Rule 2: Strict Negative Constraints**: สั่งห้ามเส้นขอบ (`border: none`) และห้ามเงา (`box-shadow: none`) เด็ดขาด
3. **Rule 3: Radius Scale Hierarchy**: องค์ประกอบด้านนอกต้องมนกว่าด้านในเสมอ (`Card 20px -> Row 14px -> Button 10px -> Tag 8px`)
4. **Rule 4: UX Flow Speed Limit**: การรายงานน้ำท่วมต้องเสร็จสิ้นภายใน **≤ 3 Taps**
5. **Rule 5: Thai-First Human UX**: ภาษาไทยเข้าใจง่าย ไร้ศัพท์เทคนิค และไม่มีวงเล็บภาษาอังกฤษ

---

### 5.2 การอ้างอิงไฟล์บริบทด้วยสัญลักษณ์ `@` (@rules.md, @design.md)
ทุกครั้งที่สั่งงาน AI ให้พิมพ์ `@` เพื่อดึงไฟล์ข้อกำหนดเข้ามาเป็นบริบท:
* `@rules.md` — เพื่อบังคับกฎเหล็กและข้อห้ามทั้งหมด
* `@design.md` — เพื่อกำหนดโทเคนสีและขนาดคอมโพเนนต์
* `@public/map_view.php` — เจาะจงไฟล์แผนที่ที่ต้องการแก้ไข
* `@public/dashboard_view.php` — เจาะจงไฟล์แดชบอร์ด

---

### 5.3 รวม Master Prompts ตามพิมพ์เขียว ROiCORE

#### 🎯 Prompt 1: สั่งสร้าง/ปรับปรุง แผนที่น้ำท่วม (Map View)
```text
@rules.md @design.md @public/map_view.php
ช่วยปรับปรุงหน้าแผนที่ OpenStreetMap ให้รองรับฟังก์ชันต่อไปนี้:
1. แสดงแผนที่เต็มจอ 100vw, 100vh หมุดน้ำท่วมใช้สีตามระดับความเสี่ยง (แดง/ส้ม/เหลือง/น้ำเงิน)
2. เชื่อมต่อเลเยอร์ดาวเทียม GISTDA 77 จังหวัด พร้อมปุ่ม Switch เปิด-ปิด
3. วาดอาณาเขต Dynamic Flood Polygon Zones อัตโนมัติเมื่อพบจุดท่วมวิกฤตหรือรายงานซ้ำซ้อน
4. ปฏิบัติตามธีม White Modern UI (ห้ามมีเส้นขอบ border และห้ามมีเงา box-shadow ทุกกรณี)
5. มีปุ่ม Floating Action Button สีน้ำเงินมุมขวาล่างสำหรับเปิดฟอร์มแจ้งเหตุน้ำท่วมด่วน
```

#### 🎯 Prompt 2: สั่งสร้างฟอร์มรายงานด่วน (Quick Report Modal ≤ 3 Taps)
```text
@rules.md @public/map_view.php
ช่วยสร้าง Modal รายงานเหตุน้ำท่วมด่วนแบบ Compact (≤ 3 Taps Flow):
1. ช่องที่ 1: เลือก 4 ระดับน้ำ (เล็กน้อย, ปานกลาง, รถเล็กผ่านไม่ได้, วิกฤตจมมิด)
2. ช่องที่ 2: เบอร์โทรศัพท์ติดต่อ (จดจำค่าลง LocalStorage อัตโนมัติ)
3. ช่องที่ 3: ปุ่มเลือกรูปถ่ายสถานที่จริง (Optional)
4. ดึงพิกัด Geolocation GPS อัตโนมัติทันทีที่เปิดฟอร์ม
5. ภาษาไทยล้วน ไม่มีศัพท์เทคนิค ไม่มีวงเล็บภาษาอังกฤษ ไร้เส้นขอบและไร้เงา
```

#### 🎯 Prompt 3: สั่งสร้าง/ปรับปรุง แดชบอร์ดติดตามสถานการณ์ (Dashboard View)
```text
@rules.md @design.md @public/dashboard_view.php
ช่วยปรับปรุงหน้าแดชบอร์ดติดตามสถานการณ์น้ำท่วม:
1. แถบสถิติสรุปภาพรวม 4 กล่อง KPI (จังหวัดที่ประสบภัย, จุดวิกฤต, รายงานวันนี้, ดัชนีความเสี่ยงฝน)
2. แถบตัวกรอง: ค้นหาและเลือกดูได้ครบ 77 จังหวัดทั่วไทย, กรองระดับความรุนแรง, สลับข้อมูล GISTDA/ประชาชน
3. ตารางรายการจุดรายงาน: กดเพื่อเปิด Drawer ดูรูปถ่ายขนาดใหญ่และแผนที่พิกัด
4. ดีไซน์แบบ Surface Layering ไร้เส้นขอบ Spacing กระชับ (Padding 12px - 16px)
```

---

## 6. ขั้นตอนที่ 5: วงจรการส่งงานด้วย Git (Pull, Add, Commit, Push)

```
              ┌──────────────────────────────────────────────┐
              │           GitHub Remote Repository           │
              └──────────────┬────────────────▲──────────────┘
                             │                │
                    git pull │                │ git push
                             ▼                │
              ┌───────────────────────────────┴──────────────┐
              │           Local Working Directory            │
              │  (แก้โค้ด Front-End ตาม rules.md/design.md)  │
              └──────────────┬───────────────────────────────┘
                             │
                    git add  │ (ย้ายเข้า Staging Area)
                             ▼
              ┌──────────────────────────────────────────────┐
              │                Staging Area                  │
              └──────────────┬───────────────────────────────┘
                             │
                  git commit │ (บันทึก Snapshot ในเครื่อง)
                             ▼
              ┌──────────────────────────────────────────────┐
              │              Local Git History               │
              └──────────────────────────────────────────────┘
```

### 6.1 ดึงโค้ดล่าสุดก่อนเริ่มงานเสมอ (Git Pull)
```bash
git pull origin main
```

### 6.2 ตรวจสอบและเลือกไฟล์เตรียมส่ง (Git Status & Git Add)
```bash
# ตรวจสอบไฟล์ที่มีการแก้ไข
git status

# นำไฟล์ทั้งหมดเข้าสู่ Staging Area
git add -A
```

### 6.3 บันทึกประวัติเวอร์ชัน (Git Commit)
```bash
git commit -m "feat(ui): ปรับปรุงระบบแผนที่และฟอร์มรายงานด่วน 3-tap"
```

### 6.4 ส่งโค้ดขึ้น GitHub (Git Push)
```bash
git push origin main
```

---

## 7. การตรวจสอบและเช็กลิสต์คุณภาพ (Quality Checklist)

ก่อนส่งมอบงานหรือทำ Git Commit ให้ตรวจสอบตามเช็กลิสต์นี้ทุกครั้ง:

- [x] **No Border**: ไม่มี `border: 1px ...` หรือเส้นขอบตีกรอบแม้แต่จุดเดียว
- [x] **No Shadow**: ไม่มี `box-shadow` หรือ `drop-shadow`
- [x] **Surface Layering**: พื้นผิวแยกชั้นชัดเจน (`#F1F5F9` -> `#FFFFFF` -> `#F8FAFC` -> `#E2E8F0`)
- [x] **Radius Scale**: องค์ประกอบชั้นนอกมนกว่าชั้นในเสมอ (`20px` > `14px` > `10px` > `8px`)
- [x] **Fast Action**: รายงานน้ำท่วมทำได้ภายใน ≤ 3 Taps
- [x] **Thai First**: ภาษาไทยล้วน ไม่มีศัพท์เทคนิค ไม่มีวงเล็บภาษาอังกฤษ
- [x] **Responsive**: แสดงผลสวยงามทั้งบนหน้าจอมือถือและจอคอมพิวเตอร์
- [x] **Spatial Intelligence**: หมุด, เลเยอร์ GISTDA 77 จังหวัด และ Polygon แสดงผลถูกต้อง

---

## 8. ตารางสรุปคำสั่งลัดและคีย์บอร์ด (Cheat Sheet)

### คำสั่ง Git ที่ใช้เป็นประจำ
| คำสั่ง | ความหมาย |
|---|---|
| `git clone <url>` | ดาวน์โหลดโปรเจกต์ลงมาในเครื่องครั้งแรก |
| `git pull origin main` | ดึงโค้ดอัปเดตล่าสุดจาก GitHub |
| `git status` | ตรวจสอบสถานะไฟล์ที่แก้ไข |
| `git add -A` | เตรียมไฟล์ทั้งหมดเข้า Staging Area |
| `git commit -m "ข้อความ"` | บันทึกประวัติการเปลี่ยนแปลง |
| `git push origin main` | ส่งโค้ดขึ้นสู่ GitHub |
| `git restore .` | ล้างการแก้ไขที่ยังไม่ commit กลับไปเป็นสถานะล่าสุด |

### คีย์ลัดใน Google Antigravity IDE
| คีย์ลัด (Windows) | คีย์ลัด (macOS) | หน้าที่ |
|---|---|---|
| `Ctrl + K` แล้ว `Ctrl + O` | `Cmd + O` | เปิดโฟลเดอร์โปรเจกต์ (Open Folder) |
| `Ctrl + ~` | `Cmd + ~` | เปิด/ปิด หน้าต่าง Terminal |
| `Ctrl + L` | `Cmd + L` | เปิด/สลับไปที่หน้าต่าง AI Chat |
| `Ctrl + P` | `Cmd + P` | ค้นหาและเปิดไฟล์ด่วน |
| `Ctrl + S` | `Cmd + S` | บันทึกไฟล์ (Save) |
| `Shift + Alt + F` | `Shift + Option + F` | จัดระเบียบโค้ดอัตโนมัติ (Format Code) |
| พิมพ์ `@` ในช่อง Chat | พิมพ์ `@` ในช่อง Chat | แทรกไฟล์บริบท (@rules.md, @design.md) |

---

> **ROiCORE** — Designed with Precision, Built with Clean Architecture on Google Antigravity IDE.
