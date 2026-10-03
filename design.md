# ROiCORE — Design System & Front-End Prompting Specifications

> **RO<span style="color:#2563EB;font-weight:800;font-style:normal">i</span>CORE** — Flood Intelligence Platform  
> **คู่มือระบบงานดีไซน์ (Design System) และข้อกำหนดเฉพาะสำหรับการเขียน Prompt ฝั่ง Front-End**  
> ปรัชญาหลัก: **White Modern Architecture (No Border, No Shadow)** ผสาน **Surface Layering** และ **Perfect Radius Hierarchy**

---

## 1. อัตลักษณ์ของแบรนด์ (Brand Identity & Typography)

### 1.1 โลโก้และสีประจำแบรนด์ (Wordmark Rule)
* **รูปแบบตัวอักษร**: `RO` + **`i`** + `CORE` -> **`ROiCORE`**
* **กฎของตัว `i`**: ต้องเป็นตัวพิมพ์เล็ก (lowercase), **ตั้งตรงไม่อียง (Non-italic)** และใช้สีน้ำเงินโมเดิร์น **Modern Blue (`#2563EB`)** เสมอ
* ตัวอักษร `RO` และ `CORE` เป็นสีเข้มหลักของระบบ (`#0F172A`)

```html
<!-- โค้ดมาตรฐานสำหรับแสดงโลโก้ ROiCORE ในทุกหน้าจอ -->
<span style="font-family:'Inter',sans-serif; font-weight:800; font-size:22px; color:#0F172A; letter-spacing:-0.03em;">
    RO<span style="color:#2563EB; font-style:normal;">i</span>CORE
</span>
```

### 1.2 ลำดับชั้นตัวอักษร (Typography Hierarchy Scale)
ใช้ฟอนต์ **Noto Sans Thai** สำหรับภาษาไทย และ **Inter** สำหรับตัวเลขและภาษาอังกฤษ:

| ระดับชั้น | การใช้งาน | ขนาด (Desktop) | ขนาด (Mobile) | น้ำหนัก (Weight) | สีตัวอักษร |
|---|---|---|---|---|---|
| **Brand Title** | โลโก้แบรนด์ | `24px` | `20px` | 800 (ExtraBold) | `#0F172A` / `#2563EB` |
| **Heading 1 (H1)** | หัวข้อหน้าจอหลัก | `18px` | `16px` | 700 (Bold) | `#0F172A` |
| **Heading 2 (H2)** | หัวข้อการ์ด / ส่วนย่อย | `15px` | `14px` | 600 (SemiBold) | `#0F172A` |
| **Body Text** | เนื้อหาและป้ายฟอร์ม | `13.5px` | `13px` | 500 (Medium) | `#0F172A` |
| **Muted Text** | คำอธิบายรอง / เวลา | `12px` | `11.5px` | 400 (Regular) | `#64748B` |
| **Stat Numbers** | ตัวเลขสถิติบน KPI | `26px` | `22px` | 700 (Bold) | ตาม Semantic Status |

---

## 2. ระบบดีไซน์โทเคน (Design Tokens Architecture)

คัดลอกชุด CSS Variables นี้ไปใส่ในส่วน `<style>` ของทุกหน้าจอ Front-End เสมอ:

```css
:root {
    /* 1. Brand Colors */
    --accent-blue: #2563EB;        /* สีน้ำเงินหลัก */
    --accent-blue-hover: #1D4ED8;  /* สีเมื่อเมาส์ชี้ */
    --accent-tint: #EFF6FF;        /* สีน้ำเงินจางสำหรับพื้นหลังไฮไลต์ */

    /* 2. Surface Layering (มิติความลึกด้วยระดับสีพื้นผิว แทนการใช้เงาและขอบ) */
    --bg-body: #F1F5F9;            /* Slate 100 — พื้นหลังจอ */
    --surface: #FFFFFF;            /* Pure White — การ์ดหลัก, Sheet, Navbar */
    --surface-subtle: #F8FAFC;     /* Slate 50 — รายการแถวย่อย, ช่อง input */
    --surface-active: #E2E8F0;     /* Slate 200 — สถานะถูกเลือก, ปุ่มรอง */
    --surface-hover: #CBD5E1;      /* Slate 300 — สถานะเมาส์ชี้ปุ่มรอง */

    /* 3. Typography Colors */
    --text-main: #0F172A;          /* Slate 900 — ข้อความหลัก */
    --text-muted: #64748B;         /* Slate 500 — ข้อความรอง/คำอธิบาย */
    --text-light: #94A3B8;         /* Slate 400 — เส้นแบ่งจางๆ หรือตัวเลขไม่สำคัญ */

    /* 4. Semantic Status & Risk Colors */
    --risk-critical: #DC2626;      /* สีแดง — ระดับวิกฤต */
    --risk-critical-bg: #FEF2F2;
    --risk-high: #EA580C;          /* สีส้ม — ระดับสูง */
    --risk-high-bg: #FFF7ED;
    --risk-medium: #D97706;        /* สีเหลืองอำพัน — ปานกลาง */
    --risk-medium-bg: #FEF3C7;
    --risk-low: #2563EB;           /* สีน้ำเงิน — เฝ้าระวัง/เล็กน้อย */
    --risk-low-bg: #EFF6FF;
    --status-success: #16A34A;     /* สีเขียว — ปกติ/ปลอดภัย */
    --status-success-bg: #F0FDF4;
    --gistda-purple: #7C3AED;      /* สีม่วง — ข้อมูลดาวเทียม GISTDA */
    --gistda-tint: #EDE9FE;

    /* 5. Radius Hierarchy Scale (องค์ประกอบนอกมนกว่าในเสมอ) */
    --r-sm: 8px;                   /* ป้ายเล็ก, ไอคอนแท็ก */
    --r-md: 10px;                  /* ปุ่มกด, ช่องกรอกข้อมูล, ดรอปดาวน์ */
    --r-lg: 14px;                  /* กล่องย่อยภายในการ์ด, รายการแถว */
    --r-xl: 20px;                  /* การ์ดหลัก, Modal, Bottom Sheet */
    --r-full: 9999px;              /* แคปซูลสถานะ, ไอคอนวงกลม, FAB */
}

/* Global Reset: ตัด Border และ Shadow ทุกกรณี */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}
```

---

## 3. พิมพ์เขียวคอมโพเนนต์หลัก (Component Specifications)

### 3.1 การ์ดหลักและกล่องย่อย (Card & Sub-Box Layering)
```
┌────────────────────────────────────────────────────────┐
│ Main Surface Card (--surface: #FFFFFF, radius: 20px)   │
│ Padding: 16px - 20px                                   │
│                                                        │
│ ┌────────────────────────────────────────────────────┐ │
│ │ Inner Subtle Box (--surface-subtle: #F8FAFC)       │ │
│ │ Radius: 14px | Padding: 12px                       │ │
│ │                                                    │ │
│ │  [ ไอคอน ] ข้อความหัวข้อย่อย       [ ปุ่ม/สถานะ ]  │ │
│ └────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────┘
```

### 3.2 ปุ่มกด (Buttons Architecture)
1. **Primary Action Button (`.btn-primary`)**:
   * พื้นหลัง: `var(--accent-blue)` (`#2563EB`)
   * ตัวอักษร: สีขาว `#FFFFFF`, ขนาด `13.5px`, Weight `600`
   * รัศมีความโค้ง: `var(--r-md)` (`10px`)
   * Padding: `10px 16px` | Gap ระหว่างไอคอนกับข้อความ: `8px`
   * Hover: `var(--accent-blue-hover)` (`#1D4ED8`)
2. **Secondary / Subtle Button (`.btn-subtle`)**:
   * พื้นหลัง: `var(--surface-active)` (`#E2E8F0`)
   * ตัวอักษร: `var(--text-main)` (`#0F172A`), ขนาด `13px`, Weight `500`
   * รัศมีความโค้ง: `var(--r-md)` (`10px`)

---

### 3.3 แผนที่และเลเยอร์เชิงพื้นที่ (Spatial Map & GISTDA Layers)
* **แผนที่ OpenStreetMap (Leaflet.js)**:
  * Fullscreen Container: `100vw`, `100vh`
  * Floating Top Navbar: `top: 16px`, `left: 16px`, `right: 16px`, `z-index: 1000`
* **หมุดจุดน้ำท่วม (Custom Markers)**:
  * ใช้วงกลมขนาด `32px` พร้อมไอคอน Font Awesome ตรงกลาง
  * สีตามระดับความเสี่ยง (แดง/ส้ม/เหลือง/น้ำเงิน) พร้อมแอนิเมชันกระพริบเมื่อเป็นระดับวิกฤต
* **Dynamic Flood Polygon Zones**:
  * วาดรูปหลายเหลี่ยมโปร่งแสง (`fillOpacity: 0.2`, `weight: 0`, ไร้เส้นขอบ) ครอบคลุมจุดที่เกิดน้ำท่วมซ้ำซ้อน
* **GISTDA 77 Provinces Layer**:
  * ใช้สีม่วงโปร่งแสง (`var(--gistda-purple)`) แสดงขอบเขตพื้นที่น้ำท่วมจากภาพถ่ายดาวเทียม

---

### 3.4 ฟอร์มรายงานด่วน (Quick Report Modal ≤ 3 Taps)
* **โครงสร้างการวางตัวเลือกระดับน้ำ**:
  * Grid 2 คอลัมน์ (4 ปุ่มกดขนาดพอดีนิ้ว)
  * แต่ละปุ่มเป็นพื้นหลัง `var(--surface-subtle)` เมื่อคลิกจะเปลี่ยนเป็น `var(--accent-tint)` พร้อมไอคอนสีน้ำเงิน
* **ฟิลด์เบอร์โทรและรูปถ่าย**:
  * วางแนวนอนแบบกะทัดรัด ไม่ดันฟอร์มให้ยาวจนต้องเลื่อนจอ

---

### 3.5 แดชบอร์ดวิเคราะห์สถานการณ์ (Dashboard View Layout)
* **KPI Cards Grid**: 4 คอลัมน์บนจอคอมพิวเตอร์ / 2 คอลัมน์บนมือถือ
* **ตัวกรองจังหวัด (Provincial Selector)**: ค้นหาและกรองได้ครบทั้ง 77 จังหวัด
* **ตารางจุดเกิดเหตุและ Drawer รายละเอียด**:
  * ตารางแบบไร้เส้นขอบ แถวคี่-คู่ใช้สีสลับอ่อนๆ (`#FFFFFF` และ `#F8FAFC`)
  * เมื่อคลิกแถว จะเปิดแผง Drawer ด้านข้างแสดงรูปถ่ายขนาดใหญ่และแผนที่พิกัด

---

## 4. คู่มือการ Prompt แบบ Design-First สำหรับทีม Front-End

เมื่อสั่งให้ Antigravity IDE สร้างหรือแก้ไข UI ให้ระบุบล็อก Design Token และ Layout ชัดเจนดังตัวอย่าง:

### 4.1 Prompt สั่งสร้างคอมโพเนนต์ตามดีไซน์เป๊ะๆ (Copy & Paste)
```text
สร้าง UI สำหรับ [ชื่อคอมโพเนนต์] โดยปฏิบัติตาม design.md อย่างเคร่งครัด:

1. ใช้ CSS Variables:
   - พื้นหลังหลัก: var(--surface) (#FFFFFF)
   - พื้นหลังกล่องย่อย: var(--surface-subtle) (#F8FAFC)
   - สีปุ่มหลัก: var(--accent-blue) (#2563EB)
2. ปฏิบัติตาม Radius Hierarchy:
   - กรอบนอกสุด: var(--r-xl) (20px)
   - แถวข้อมูลด้านใน: var(--r-lg) (14px)
   - ปุ่มและตัวเลือก: var(--r-md) (10px)
3. ปิด Border และ Shadow ทั้งหมด (ห้ามมีเส้นขอบและเงา)
4. จัด Spacing ให้กะทัดรัด (Padding 12px - 16px, Gap 8px - 12px)
5. แสดงผลภาษาไทยล้วน ไม่มีศัพท์เทคนิค และไม่มีวงเล็บภาษาอังกฤษ
```

---

## 5. การตรวจสอบความสอดคล้องของดีไซน์ (Design Audit Checklist)

- [x] **Zero Border**: ไม่มี `border: 1px ...` หลงเหลืออยู่
- [x] **Zero Shadow**: ไม่มี `box-shadow` หรือ `drop-shadow`
- [x] **Surface Contrast**: แยกสัดส่วนด้วย Slate 100 / Pure White / Slate 50 ชัดเจน
- [x] **Typography Hierarchy**: ฟอนต์อ่านง่าย หัวข้อ Bold (700) เนื้อหา Regular/Medium
- [x] **Icon Alignment**: ไอคอน Font Awesome 6 วางคู่ข้อความด้วย `gap: 6px-8px` จัดกึ่งกลางพอดี
- [x] **Fluid Responsive**: หน้าจอปรับตัวได้อย่างเป็นธรรมชาติ ไม่เกิด Horizontal Scrollbar บนมือถือ
