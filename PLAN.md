# ROICORE — Project Plan

> **RO<span style="color:#2563eb;font-style:normal">i</span>CORE** — Flood Intelligence Platform
> Core discipline: **OOP & OOAD** | Theme: **White Modern UI (No Border)**

---

## 1. Brand Identity

| Item | Spec |
|---|---|
| Wordmark | `RO` + **`i` (accent โทนน้ำเงิน ตั้งตรง)** + `CORE` |
| Accent color | Modern Blue (โทนน้ำเงิน) — `#2563EB` (primary), `#1D4ED8` (hover), `#EFF6FF` (tint) |
| Neutral | `#FFFFFF` bg · `#F8FAFC` surface · `#0F172A` text · `#64748B` muted |
| Typography | Inter / Noto Sans Thai (รองรับไทย) |
| Logo rule | ตัว `i` ตั้งตรง (ไม่อียง) สีน้ำเงิน (`#2563EB`) เท่านั้น — ตัวอักษรอื่น เป็น `#0F172A` |

---

## 2. Design System — "White Modern (No Border, No Shadow)"

### 2.1 Principles
- **No Border & No Shadow** = ไม่ใช้เส้นขอบ (border / outline) และ**ห้ามใช้เงา (box-shadow)** เป็นองค์ประกอบใน UI เด็ดขาด แทนที่ด้วย: พื้นหลังคนละระดับ (Surface Layering: `bg-white` บน `bg-slate-100/50`), ระยะห่าง (Spacing: padding/gap), และ Typographic Hierarchy
- **No Wasteful Badges** = **ห้ามใช้ badge สิ้นเปลือง** ไม่แปะป้ายสถานะหรือแท็กที่ไม่มีประโยชน์ต่อการใช้งานจริง
- **Thai First & User-Centric** = **ใช้ภาษาไทยเป็นหลัก** ในการสื่อสารกับผู้ใช้งาน **ห้ามใช้ศัพท์เชิงเทคนิคบนหน้าเว็บ** และ**ห้ามมีวงเล็บภาษาอังกฤษ** ในข้อความ
- **Perfect Border Radius** = ค่า radius คงที่และกลมกลืนทุกจุด (ไม่ผสมมุมคมกับมุมมน)

### 2.2 Radius Scale (ระบบเดียวทั้งแอป)
| Token | Value | ใช้กับ |
|---|---|---|
| `--r-sm` | `8px` | chip, tag, badge, input เล็ก |
| `--r-md` | `12px` | button, input, dropdown |
| `--r-lg` | `16px` | card, list item |
| `--r-xl` | `24px` | modal, sheet, bottom-sheet |
| `--r-full` | `9999px` | avatar, pill, FAB, toggle |

> Rule: **องค์ประกอบชั้นนอกใช้ radius ใหญ่กว่าชั้นในเสมอ** (card 16 → row 12 → chip 8)

### 2.3 Surface Layering (แทน Shadow และ Border)
| Surface Token | Color / Style | ใช้กับ |
|---|---|---|
| `--bg-body` | `#F1F5F9` (Slate 100) | พื้นหลังหลักของหน้าจอ |
| `--surface` | `#FFFFFF` | Card, Sheet, Modal หลัก |
| `--surface-subtle` | `#F8FAFC` (Slate 50) | Inner row, List item, Input container |
| `--surface-active` | `#E2E8F0` (Slate 200) | Pressed state, Active chip |

> Rule: **ห้ามใช้ box-shadow หรือ drop-shadow ใด ๆ ทั้งสิ้น** — แยกมิติด้วยความต่างระดับของสีพื้นผิว (Surface Contrast) เท่านั้น

### 2.4 Components
`Button (primary=blue, ghost=plain)` · `Card` · `Input` · `Chip/Tag` · `Bottom Sheet` · `Modal` · `Toast` · `Map Marker (pin น้ำเงิน)` · `Status Pill (น้ำท่วม/เฝ้าระวัง/ปกติ)`

---

## 3. Services

| Service | ใช้ทำอะไร | โหมด |
|---|---|---|
| **OpenStreetMap** | basemap + จุดน้ำท่วม (Leaflet / MapLibre + vector tiles) | ฟรี ไม่ต้อง key |
| **OpenWeather** | สภาพอากาศเรียลไทม์ + ฝนพยากรณ์ เพื่อประเมินความเสี่ยงน้ำท่วม | API key (free tier) |
| **Supabase Local** | Auth (เบอร์โทร OTP), Postgres, Realtime, Storage | `supabase start` (Docker) → deploy ขึ้น Cloud |

**Mapping ข้อมูลเชื่อมโยงกึ่งกลาง:**
- Supabase = source of truth (รายงาน, ผู้ใช้, สถานะจุด)
- OSM = แผนที่แสดงผล (overlay จุดรายงานจาก Supabase)
- OpenWeather = enrich แต่ละจุด/พื้นที่ (ฝนสะสม, ความเสี่ยง)

---

## 4. OOP & OOAD

### 4.1 OOAD Process (UML)
1. **Use Case Diagram** — ผู้ประสบอุทกภัย / ผู้เดินทาง / (แอดมิน)
2. **Class Diagram** — domain model หลัก (ดู 4.2)
3. **Sequence Diagram** — Quick Report flow, Flood-check flow
4. **Activity Diagram** — workflow รายงาน + ยืนยันตัวตน OTP

### 4.2 Domain Model (Class Diagram แกนกลาง)

```
<<abstract>> User
├─ FloodVictim
│    + quickReport()
│    + confirmIdentity(phone)
└─ Traveler
     + checkFloodPoints(location)
     + requestRouteAdvice()

FloodReport            Report*
├─ id, location(GeoPoint), severity, description
├─ photos: Media[]
├─ status: ReportStatus  <<enum PENDING|VERIFIED|RESOLVED>>
└─ submit() / verify() / resolve()

FloodPoint             Point-of-interest บนแผนที่
├─ geoPoint, radius, waterLevel
├─ source: Report aggregation
└─ riskLevel(): calculateRisk()

WeatherService  <<interface>>
└─ getWeather(lat,lng): WeatherData
   └─ OpenWeatherService : WeatherService   (Adapter)

MapService     <<interface>>
└─ OSMMapService : MapService               (Leaflet/MapLibre adapter)

Repository<T>  <<interface>>
├─ FloodReportRepository  -> Supabase (table: flood_reports)
├─ UserRepository         -> Supabase Auth (phone OTP)
└─ FloodPointRepository   -> Supabase (table: flood_points)

Notifier <<interface>> -> Realtime subscription (Supabase Realtime)
```

**Pattern ที่ใช้:**
| Pattern | ที่ |
|---|---|
| **Adapter** | `OpenWeatherService`, `OSMMapService` ห่อ SDK ภายนอก |
| **Repository** | แยก data layer จาก domain (สลับ Local/Cloud ได้) |
| **Factory** | `ReportFactory` สร้าง report ตาม severity/type |
| **Observer** | Supabase Realtime → จุดน้ำท่วมอัปเดตสด |
| **Strategy** | `RiskStrategy` (ฝน, รายงานผู้ใช้, ระดับน้ำ) |
| **Singleton** | `SupabaseClient`, `SessionManager` |

### 4.3 Layering (Clean-ish OOP)
```
UI (Components/Views)
   ↓
ViewModel / Controller (state + use cases)
   ↓
Domain (entities, interfaces, strategies)
   ↓
Data (Repository impl → Supabase / OpenWeather / OSM)
```

---

## 5. Basic System — 2 Flows หลัก

### Flow A — ผู้ประสบอุทกภัย: Quick Report
```
เปิดแอป
 → geolocate ตำแหน่งปัจจุบัน (OSM)
 → Quick Report (bottom sheet, radius-xl)
 → ยืนยันตัวตนด้วยเบอร์โทร (Supabase Auth → OTP)
 → ใส่/ยืนยันระดับน้ำ + รูป (optional)
 → submit → flood_reports (status=PENDING)
 → Observer: จุดโผล่บนแผนที่ (realtime)
```
> ออกแบบให้ **≤ 3 taps** — ยืนยันเบอร์ครั้งเดียว ครั้งถัดไป auto-fill

### Flow B — ผู้เดินทาง: เช็คจุดน้ำท่วม
```
เปิดแอป
 → แผนที่ (OSM) + พินจุดน้ำท่วมจาก flood_points
 → จุดแต่ละพิน: severity + weather จาก OpenWeather
 → กรอง/ค้นหาพื้นที่ หรือเส้นทาง
 → ดูรายละเอียด (card, surface-subtle)
```
> **ไม่ต้อง login** (guest mode) — ตัด friction ฝั่งผู้เดินทาง

---

## 6. Tech Stack (ข้อเสนอ)

| Layer | เลือก |
|---|---|
| App | **Flutter** (mobile-first, white UI ทำได้ไว) หรือ **Next.js PWA** |
| Map | Leaflet / MapLibre GL + OSM tiles |
| Weather | OpenWeather One Call 3.0 |
| Backend | Supabase (Auth phone OTP, Postgres, Realtime, Storage) |
| Local dev | `supabase init` → `supabase start` (Docker) |
| Deploy | Supabase Cloud (`supabase link` + `db push`) |
| State | Riverpod (Flutter) / Zustand (Web) |

---

## 7. Data Model (Supabase)

```sql
-- ผู้ใช้ (extends from auth.users)
profiles(id uuid pk, phone text, role text /*victim|traveler|admin*/, created_at)

-- รายงานน้ำท่วม
flood_reports(
  id uuid pk,
  user_id uuid fk,
  geo geography(Point,4326),
  severity smallint,        -- 1 ต่ำ, 2 ปานกลาง, 3 สูง
  description text,
  photos text[],            -- storage paths
  status text default 'PENDING',
  created_at timestamptz
)

-- จุดน้ำท่วม (aggregate / admin-confirmed)
flood_points(
  id uuid pk,
  geo geography(Point,4326),
  water_level numeric,
  risk_level text,          -- low|medium|high
  source text,              -- report|weather|manual
  updated_at timestamptz
)

-- realtime: subscribe เปลี่ยนแปลง flood_points + flood_reports
```

---

## 8. Milestones

| # | Phase | Deliverable |
|---|---|---|
| **M0** | Setup | repo, Supabase local (`supabase start`), OSM tiles, design tokens (radius/surfaces) |
| **M1** | OOAD | Use Case + Class + Sequence diagrams (Mermaid ใน `docs/uml/`) |
| **M2** | Shell | White Modern UI kit: Button/Card/Sheet/Marker + Brand `RO`**`i`**`CORE` |
| **M3** | Flow B | แผนที่ OSM + พินจุดน้ำท่วม + OpenWeather widget (guest) |
| **M4** | Flow A | Quick Report + Phone OTP + upload รูป + realtime sync |
| **M5** | Intelligence | Risk strategy (ฝน × รายงาน) + route advice |
| **M6** | Deploy | Supabase Cloud, เปิดใช้งานจริง |

---

## 9. Repo Structure (แผน)

```
roicore/
├─ PLAN.md
├─ docs/
│  └─ uml/            usecase.md · class.md · sequence-report.md · sequence-check.md
├─ supabase/
│  ├─ config.toml
│  └─ migrations/     0001_init.sql
├─ src/
│  ├─ core/           theme (tokens) · constants
│  ├─ domain/         entities · interfaces · strategies
│  ├─ data/           repositories · services (weather/map adapters)
│  ├─ presentation/   screens · widgets
│  └─ app.dart / main.tsx
└─ README.md
```

---

## 10. Definition of Done (ต่อ feature)
- [ ] มี Class/Sequence diagram กำกับ (OOAD)
- [ ] โค้ดแยกชั้น domain/data/UI, ใช้ interface ไม่ผูกกับ SDK ตรง ๆ
- [ ] ผ่าน White Modern rule: **ไม่มีเส้นขอบและห้ามใช้ shadow** · radius และ surface contrast ตรง token
- [ ] ผ่าน Content & UI rule: **ห้ามใช้ badge สิ้นเปลือง** · **ใช้ภาษาไทยเป็นหลัก** · **ห้ามใช้ศัพท์เชิงเทคนิคบนหน้าเว็บ** · **ห้ามมีวงเล็บภาษาอังกฤษ**
- [ ] Flow A ≤ 3 taps · Flow B ไม่ต้อง login
- [ ] ทดสอบบน Supabase Local แล้ว deploy ขึ้น Cloud ได้
