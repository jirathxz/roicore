# ROiCORE — Design System & Theme Guidelines

> **RO<span style="color:#2563EB;font-style:normal">i</span>CORE** — Flood Intelligence Platform  
> ข้อกำหนดการออกแบบ (Design Specifications) และระบบโทเคน (Design Tokens) สำหรับส่วนต่อประสานผู้ใช้

---

## 1. ปรัชญาการออกแบบหลัก (Core Principles)

### 1.1 Visual Hierarchy (การจัดลำดับการมองเห็นที่เด่นชัด)
- จัดลำดับความสำคัญของข้อมูลให้ผู้ใช้รับรู้ได้ทันทีด้วย **ขนาดตัวอักษร (Typography)**, **ความเข้มของน้ำหนักสี (Color Weight)** และ **ระดับชั้นของพื้นผิว (Surface Layering)**
- ข้อมูลสำคัญที่สุด (Primary Actions, สถานะหลัก, พิกัด) จะต้องโดดเด่นทันทีที่เปิดหน้าจอ โดยไม่จำเป็นต้องพึ่งพาเส้นขอบหรือสีสันที่ฉูดฉาด

### 1.2 Compact & High-Density UI (กะทัดรัด เข้าถึงง่าย ประหยัดพื้นที่)
- ออกแบบให้จัดวางข้อมูลได้อย่างมีประสิทธิภาพ (Information Density สูง) โดยไม่รู้สึกอึดอัด
- ใช้ Spacing และ Padding ที่กระชับ (Compact Spacing: 6px–16px) เพื่อให้เห็นภาพรวมสถานการณ์ได้ครบถ้วนในหน้าจอเดียวโดยไม่ต้องเลื่อนหน้าจอมากเกินไป
- ปรับขนาดฟอร์มและปุ่มกดให้พอดีมือและสายตา ไม่เทอะทะ

### 1.3 White Modern (No Border, No Shadow)
- **ห้ามใช้เส้นขอบ (Border / Outline)** ในทุกองค์ประกอบ
- **ห้ามใช้เงา (Box-shadow / Drop-shadow)** เด็ดขาด
- มิติและความลึกถูกสร้างขึ้นจากความต่างระดับของสีพื้นหลังและสีพื้นผิว (Surface Contrast)

### 1.4 No Wasteful Badges (หลีกเลี่ยงการใช้ Badge สิ้นเปลือง)
- ไม่ใส่ป้ายกำกับ (Badge / Chip / Tag) พร่ำเพรื่อหรือแปะป้ายที่ไม่ได้ช่วยให้ตัดสินใจได้เร็วขึ้น
- แสดงสถานะผ่านข้อความกระชับ สีของฟอนต์ หรือไอคอนที่มีความหมายตรงตัวแทน

### 1.5 Functional Iconography (Font Awesome)
- ใช้ **Font Awesome 6** เป็นชุดไอคอนมาตรฐานของทั้งระบบ
- ไอคอนต้องทำหน้าที่สนับสนุนข้อความ (Complementary) ช่วยให้กวาดสายตาเจอฟังก์ชันที่ต้องการได้ทันที
- วางไอคอนคู่กับข้อความด้วยระยะห่างที่พอดี (`gap: 6px–8px`)

---

## 2. Brand Identity & Typography

### 2.1 Wordmark & Brand Accent
- **รูปแบบโลโก้**: `RO` + **`i`** + `CORE`
- **กฎของตัว `i`**: ต้องเป็นพิมพ์เล็ก (lowercase), **ตั้งตรง (Non-italic)** และใช้สีน้ำเงินเน้น **Modern Blue (`#2563EB`)** เท่านั้น
- ตัวอักษร `RO` และ `CORE` เป็นสีเข้มหลัก (`#0F172A`)

### 2.2 Typography Scale
| ลำดับชั้น | แบบอักษร | ขนาด (Desktop) | ขนาด (Mobile) | น้ำหนัก | สี |
|---|---|---|---|---|---|
| **Brand Logo** | Inter | `26px` | `22px` | 800 (ExtraBold) | `#0F172A` / `#2563EB` |
| **Page Title (H1)** | Noto Sans Thai | `20px` | `18px` | 700 (Bold) | `#0F172A` |
| **Section Title (H2)** | Noto Sans Thai | `15px` | `14.5px` | 700 (Bold) | `#0F172A` |
| **Body / Labels** | Noto Sans Thai | `13.5px` | `13px` | 600 / 500 | `#0F172A` |
| **Muted / Hint** | Noto Sans Thai | `12.5px` | `12px` | 400 (Regular) | `#64748B` |
| **Data / Code** | Consolas, monospace | `12.5px` | `12px` | 400 (Regular) | `#1E293B` |

---

## 3. Design Tokens (ระบบโทเคนมาตรฐาน)

### 3.1 Color Tokens
```css
:root {
    /* Brand Accent */
    --accent-blue: #2563EB;
    --accent-blue-hover: #1D4ED8;
    --accent-tint: #EFF6FF;

    /* Surfaces (สร้างมิติความลึกด้วยการซ้อนชั้นสี) */
    --bg-body: #F1F5F9;        /* Slate 100 - พื้นหลังจอ */
    --surface: #FFFFFF;        /* Pure White - การ์ดหลัก */
    --surface-subtle: #F8FAFC; /* Slate 50 - กล่องเนื้อหาย่อย / พื้นหลังช่องกรอก */
    --surface-active: #E2E8F0; /* Slate 200 - ปุ่มรอง / สถานะกด */
    --surface-hover: #CBD5E1;  /* Slate 300 - โฮเวอร์ปุ่มรอง */

    /* Typography Colors */
    --text-main: #0F172A;      /* Slate 900 - ข้อความหลัก */
    --text-muted: #64748B;     /* Slate 500 - ข้อความรอง */
    --text-code: #1E293B;      /* Slate 800 - ผลลัพธ์ข้อมูล */

    /* Semantic Status Colors (ใช้ร่วมกับข้อความหรือไอคอน) */
    --status-danger: #DC2626;   /* แดง - ระดับวิกฤต */
    --status-warning: #D97706;  /* ส้ม - เฝ้าระวัง */
    --status-success: #16A34A;  /* เขียว - ปกติ / ปลอดภัย */

    /* Radius Scale */
    --r-sm: 8px;               /* ป้ายเล็ก, ไอคอนแท็ก */
    --r-md: 10px;              /* ปุ่ม, ช่องกรอก, ดรอปดาวน์ */
    --r-lg: 14px;              /* กล่องย่อยภายในการ์ด */
    --r-xl: 20px;              /* การ์ดหลัก */
    --r-full: 9999px;          /* แคปซูลสถานะ, ไอคอนกลม */
}
```

### 3.2 Spacing & Density Tokens (ความกะทัดรัด)
- **Container Max-Width**: `920px` (กะทัดรัด มองเห็นง่าย สบายตา)
- **Card Padding**: `20px–24px` (กระชับ ไม่สิ้นเปลืองขอบ)
- **Inner Box Padding**: `14px–16px`
- **Form Row Gap**: `10px`
- **Element Gap**: `6px–8px`

---

## 4. Font Awesome Iconography Guide

### 4.1 ชุดไอคอนมาตรฐานที่ใช้ในระบบ
| หน้าที่ / ฟังก์ชัน | คลาส Font Awesome | ตัวอย่างการใช้งาน |
|---|---|---|
| **รายงานสถานการณ์น้ำท่วม** | `fa-solid fa-bullhorn` | หัวข้อส่งรายงานสถานการณ์ |
| **พิกัดและตำแหน่ง** | `fa-solid fa-location-dot` | ฟิลด์ระบุละติจูด/ลองจิจูด |
| **ระดับความรุนแรง / วิกฤต** | `fa-solid fa-triangle-exclamation` | ตัวเลือกระดับความรุนแรง |
| **เบอร์โทรติดต่อ** | `fa-solid fa-phone` | ฟิลด์กรอกเบอร์โทร |
| **รายละเอียดข้อความ** | `fa-solid fa-comment-dots` | ฟิลด์กรอกรายละเอียด |
| **จุดเฝ้าระวังน้ำท่วม** | `fa-solid fa-water` | ตรวจสอบจุดน้ำท่วม |
| **สภาพอากาศและปริมาณฝน** | `fa-solid fa-cloud-rain` | ตรวจสอบฝนและสภาพอากาศ |
| **รายการข้อมูลทั้งหมด** | `fa-solid fa-list-check` | รายการรายงานทั้งหมด |
| **กล่องแสดงผลลัพธ์** | `fa-solid fa-square-poll-vertical` | แสดงข้อมูลตอบกลับ |
| **ปุ่มส่งข้อมูล** | `fa-solid fa-paper-plane` | ปุ่มยืนยันการส่งข้อมูล |
| **ปุ่มรีเฟรช / ค้นหา** | `fa-solid fa-magnifying-glass` | ปุ่มดึงข้อมูลสภาพอากาศ |

### 4.2 กฎการจัดวางไอคอน (Icon Layout Rules)
1. ไอคอนต้องมีขนาดสัมพันธ์กับข้อความ (`0.95em` – `1em`)
2. จัดกึ่งกลางแนวตั้งเสมอ (`display: inline-flex; align-items: center; gap: 6px;`)
3. ไม่ใช้ไอคอนซ้ำซ้อนหรือใส่ไอคอนที่ไม่มีความหมายในการนำทาง

---

## 5. Component Specifications (ข้อกำหนดส่วนประกอบ)

### 5.1 Buttons (ปุ่มกดขนาดกะทัดรัด)
- **Primary Button (`.btn-action`)**:
  - พื้นหลัง: `var(--accent-blue)` (`#2563EB`)
  - สีข้อความ: `#FFFFFF`
  - ฟอนต์: `13.5px`, Weight `600`
  - Padding: `10px 16px`
  - Radius: `var(--r-md)` (`10px`)
  - Hover: `var(--accent-blue-hover)` (`#1D4ED8`)
  - Icon: นำหน้าข้อความ เว้นระยะ `6px`

- **Secondary Button (`.btn-secondary`)**:
  - พื้นหลัง: `var(--surface-active)` (`#E2E8F0`)
  - สีข้อความ: `var(--text-main)` (`#0F172A`)
  - ฟอนต์: `13.5px`, Weight `600`
  - Padding: `10px 16px`
  - Radius: `var(--r-md)` (`10px`)
  - Hover: `var(--surface-hover)` (`#CBD5E1`)

### 5.2 Form Inputs & Controls
- **Form Label (`.form-label`)**: ขนาด `12.5px`, Weight `600`, สี `var(--text-main)`, มีไอคอนจิ๋วนำหน้าเพื่อความชัดเจน
- **Input / Select / Textarea**:
  - พื้นหลัง: `var(--surface)` (`#FFFFFF`) วางซ้อนบนกล่อง `var(--surface-subtle)`
  - Padding: `8px 12px` (กะทัดรัด ไม่สูงเกินไป)
  - ฟอนต์: `13.5px`
  - Radius: `var(--r-md)` (`10px`)
  - ปิดเส้นขอบทุกด้าน (`border: none; outline: none;`)

### 5.3 Cards & Sections
- **Main Card (`.card`)**: พื้นหลังขาว `var(--surface)`, Radius `20px` (`--r-xl`), Padding `20px–24px`
- **Sub-box (`.test-box`)**: พื้นหลัง `var(--surface-subtle)`, Radius `14px` (`--r-lg`), Padding `16px`

### 5.4 Data Viewer Panel
- **Viewer Box (`.response-viewer`)**:
  - พื้นหลัง: `var(--surface-subtle)`
  - Radius: `14px` (`--r-lg`), Padding: `16px`
  - ฟอนต์: `Consolas, monospace`, ขนาด `12.5px`, สี `#1E293B`
  - Max Height: `320px` พร้อม Scrollbar ภายในที่สะอาดตา

---

## 6. โครงสร้างเลย์เอาต์ (Visual Hierarchy Layout)

```
+-------------------------------------------------------------+
|               ROiCORE Container (Max 920px)                 |
|                                                             |
|  [ Header Card ]                                            |
|  ROiCORE — ระบบติดตามและแจ้งเตือนสถานการณ์น้ำท่วม           |
|                                                             |
|  [ Content Grid (Desktop: 2 Columns / Mobile: 1 Column) ]  |
|  +-----------------------------+---------------------------+|
|  | [ ส่วนส่งรายงานสถานการณ์ ]  | [ ส่วนตรวจสอบข้อมูล ]     ||
|  | - ฟอร์มระบุพิกัดและอาการ    | - จุดเฝ้าระวังน้ำท่วม     ||
|  | - ระดับความรุนแรง           | - ข้อมูลสภาพอากาศและฝน    ||
|  | - ปุ่มยืนยันส่งข้อมูล       | - รายการรายงานทั้งหมด     ||
|  +-----------------------------+---------------------------+|
|                                                             |
|  [ ส่วนแสดงผลลัพธ์ข้อมูล (Response Viewer) ]                |
+-------------------------------------------------------------+
```
