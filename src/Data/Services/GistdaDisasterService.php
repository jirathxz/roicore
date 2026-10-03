<?php

declare(strict_types=1);

namespace RoiCore\Data\Services;

use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class GistdaDisasterService
{
    private Client $httpClient;
    private ?string $apiKey;
    private string $baseUrl;

    public function __construct(?string $apiKey = null, ?Client $client = null)
    {
        $this->apiKey = $apiKey ?? ($_ENV['GISTDA_API_KEY'] ?? null);
        $this->baseUrl = $_ENV['GISTDA_API_URL'] ?? 'https://disaster.gistda.or.th/services/open-api';
        $this->httpClient = $client ?? new Client([
            'timeout' => 4.0,
        ]);
    }

    public function isOffline(): bool
    {
        return empty($this->apiKey);
    }

    /**
     * ดึงข้อมูลสถานการณ์น้ำท่วมจากดาวเทียม GISTDA (Disaster Platform)
     * รองรับทั้งการเชื่อมต่อสดผ่าน Open API และ Fallback ชุดข้อมูลดาวเทียมสมจริงในโหมดออฟไลน์
     *
     * @return array<string, mixed>
     */
    public function getFloodObservation(string $province = 'ร้อยเอ็ด'): array
    {
        if (!empty($this->apiKey)) {
            try {
                $response = $this->httpClient->get($this->baseUrl . '/flood/current', [
                    'headers' => [
                        'X-API-KEY' => $this->apiKey,
                        'Accept' => 'application/json',
                    ],
                    'query' => [
                        'province' => $province,
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $json = json_decode((string) $response->getBody(), true);
                    if (is_array($json) && !empty($json['data'])) {
                        return array_merge($json['data'], [
                            'is_offline' => false,
                            'status' => 'LIVE',
                        ]);
                    }
                }
            } catch (GuzzleException) {
                // Graceful fallback to cached satellite data
            }
        }

        // Realistic GISTDA Satellite Observation Mock Data for Roi Et (Sentinel-1 SAR)
        return [
            'status' => 'OFFLINE',
            'is_offline' => true,
            'source' => 'GISTDA Disaster Platform - Open API',
            'source_url' => 'https://disaster.gistda.or.th/services/open-api',
            'satellite' => 'Sentinel-1 SAR (Radar C-Band ทะลุเมฆฝน)',
            'resolution_meters' => 10.0,
            'observation_date' => (new DateTimeImmutable('-2 hours'))->format(DateTimeImmutable::ATOM),
            'province' => $province,
            'summary' => [
                'total_flooded_rai' => 12480,
                'total_flooded_sqkm' => 19.97,
                'agricultural_impact_rai' => 9850,
                'residential_impact_rai' => 2630,
                'alert_level' => 'วิกฤติ-เฝ้าระวังสูง (Critical Alert)',
                'daily_trend' => 'ระดับน้ำในลุ่มน้ำชียังคงเอ่อล้นตลิ่งต่อเนื่อง',
            ],
            'district_breakdown' => [
                [
                    'district' => 'เสลภูมิ',
                    'flooded_rai' => 4210,
                    'flooded_sqkm' => 6.74,
                    'percent_of_total' => 33.7,
                    'risk_level' => 'high',
                    'main_basin' => 'ลุ่มน้ำชีตอนล่าง / คลองส่งน้ำ',
                ],
                [
                    'district' => 'ธวัชบุรี',
                    'flooded_rai' => 3850,
                    'flooded_sqkm' => 6.16,
                    'percent_of_total' => 30.8,
                    'risk_level' => 'critical',
                    'main_basin' => 'ลุ่มน้ำชีตอนกลาง',
                ],
                [
                    'district' => 'โพนทอง',
                    'flooded_rai' => 2740,
                    'flooded_sqkm' => 4.38,
                    'percent_of_total' => 22.0,
                    'risk_level' => 'high',
                    'main_basin' => 'ลุ่มน้ำยัง',
                ],
                [
                    'district' => 'จังหาร',
                    'flooded_rai' => 980,
                    'flooded_sqkm' => 1.57,
                    'percent_of_total' => 7.9,
                    'risk_level' => 'medium',
                    'main_basin' => 'ลุ่มน้ำชีตอนบน / อ่างธวัชชัย',
                ],
                [
                    'district' => 'เมืองร้อยเอ็ด',
                    'flooded_rai' => 700,
                    'flooded_sqkm' => 1.12,
                    'percent_of_total' => 5.6,
                    'risk_level' => 'medium',
                    'main_basin' => 'ทางเลี่ยงเมือง / คูเมือง',
                ],
            ],
            'satellite_flood_polygons' => [
                [
                    'zone_id' => 'GISTDA_ROI_01',
                    'name' => 'แนวท่วมดาวเทียมลุ่มน้ำชี (ธวัชบุรี - เสลภูมิ)',
                    'area_sqkm' => 12.90,
                    'area_rai' => 8060,
                    'risk_level' => 'critical',
                    'coordinates' => [
                        [16.0350, 103.7300],
                        [16.0680, 103.7750],
                        [16.0520, 103.8200],
                        [16.0150, 103.8450],
                        [15.9900, 103.7900],
                        [16.0100, 103.7400],
                    ],
                ],
                [
                    'zone_id' => 'GISTDA_ROI_02',
                    'name' => 'แนวท่วมดาวเทียมลุ่มน้ำยัง (โพนทอง)',
                    'area_sqkm' => 4.38,
                    'area_rai' => 2740,
                    'risk_level' => 'high',
                    'coordinates' => [
                        [16.3250, 103.9650],
                        [16.3350, 103.9950],
                        [16.3100, 104.0150],
                        [16.2800, 103.9880],
                        [16.2850, 103.9680],
                    ],
                ],
                [
                    'zone_id' => 'GISTDA_ROI_03',
                    'name' => 'แนวท่วมดาวเทียมอ่างธวัชชัย - จังหาร',
                    'area_sqkm' => 2.69,
                    'area_rai' => 1680,
                    'risk_level' => 'medium',
                    'coordinates' => [
                        [16.0750, 103.7500],
                        [16.0920, 103.7850],
                        [16.0780, 103.8050],
                        [16.0620, 103.7800],
                    ],
                ],
            ],
        ];
    }

    /**
     * ดึงข้อมูลน้ำท่วมดาวเทียม GISTDA ทุกจังหวัด (ระดับประเทศ)
     * ครอบคลุมพื้นที่น้ำท่วมทุกภูมิภาค: ภาคเหนือ ภาคอีสาน ภาคกลาง ภาคใต้
     *
     * @return array<string, mixed>
     */
    public function getAllProvincesFloodData(): array
    {
        if (!empty($this->apiKey)) {
            try {
                $response = $this->httpClient->get($this->baseUrl . '/flood/national', [
                    'headers' => [
                        'X-API-KEY' => $this->apiKey,
                        'Accept' => 'application/json',
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $json = json_decode((string) $response->getBody(), true);
                    if (is_array($json) && !empty($json['data'])) {
                        return array_merge($json['data'], [
                            'is_offline' => false,
                            'status' => 'LIVE',
                        ]);
                    }
                }
            } catch (GuzzleException) {
                // Graceful fallback
            }
        }

        // ข้อมูลดาวเทียม GISTDA จำลองระดับประเทศ ครอบคลุมทุกจังหวัดที่ได้รับผลกระทบ
        // อ้างอิงจากสถานการณ์น้ำท่วมปี 2567 ภาพถ่าย Sentinel-1 SAR
        $now = new DateTimeImmutable();
        $observedAt = $now->modify('-1 hour')->format(DateTimeImmutable::ATOM);

        return [
            'status' => 'OFFLINE',
            'is_offline' => true,
            'source' => 'GISTDA Disaster Platform - Open API (National Coverage)',
            'source_url' => 'https://disaster.gistda.or.th/services/open-api',
            'satellite' => 'Sentinel-1 SAR + THEOS-2 (Optical)',
            'resolution_meters' => 10.0,
            'observation_date' => $observedAt,
            'scope' => 'national',
            'total_provinces_affected' => 28,
            'national_summary' => [
                'total_flooded_rai' => 2_148_600,
                'total_flooded_sqkm' => 3437.8,
                'agricultural_impact_rai' => 1_820_000,
                'residential_impact_rai' => 328_600,
                'national_alert_level' => 'เฝ้าระวังระดับสูง',
            ],
            'satellite_flood_polygons' => $this->buildNationalPolygons(),
        ];
    }

    /**
     * สร้างชุด Polygon น้ำท่วมระดับประเทศจากข้อมูลดาวเทียมจำลอง
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildNationalPolygons(): array
    {
        return [
            // ============================================
            // ภาคเหนือ (NORTH)
            // ============================================
            [
                'zone_id' => 'GISTDA_N_CM_01',
                'province' => 'เชียงใหม่',
                'region' => 'north',
                'name' => 'น้ำท่วมลุ่มน้ำปิง เชียงใหม่ (ฝาง-แม่อาย)',
                'area_sqkm' => 38.4,
                'area_rai' => 24_000,
                'risk_level' => 'critical',
                'coordinates' => [
                    [20.0600, 99.8700],
                    [20.1100, 99.9200],
                    [20.0800, 99.9800],
                    [20.0100, 99.9600],
                    [19.9700, 99.9000],
                    [20.0000, 99.8500],
                ],
            ],
            [
                'zone_id' => 'GISTDA_N_CM_02',
                'province' => 'เชียงใหม่',
                'region' => 'north',
                'name' => 'น้ำท่วมเมืองเชียงใหม่ - ดอยสะเก็ด',
                'area_sqkm' => 22.1,
                'area_rai' => 13_800,
                'risk_level' => 'high',
                'coordinates' => [
                    [18.8100, 99.0100],
                    [18.8450, 99.0700],
                    [18.8200, 99.1200],
                    [18.7700, 99.1100],
                    [18.7500, 99.0600],
                    [18.7700, 99.0000],
                ],
            ],
            [
                'zone_id' => 'GISTDA_N_LA_01',
                'province' => 'ลำปาง',
                'region' => 'north',
                'name' => 'น้ำท่วมลุ่มน้ำวัง ลำปาง',
                'area_sqkm' => 18.6,
                'area_rai' => 11_625,
                'risk_level' => 'high',
                'coordinates' => [
                    [18.3050, 99.4800],
                    [18.3400, 99.5300],
                    [18.3100, 99.5800],
                    [18.2700, 99.5650],
                    [18.2500, 99.5100],
                    [18.2800, 99.4700],
                ],
            ],
            [
                'zone_id' => 'GISTDA_N_PB_01',
                'province' => 'พะเยา',
                'region' => 'north',
                'name' => 'น้ำท่วมรอบกว๊านพะเยา - ดอกคำใต้',
                'area_sqkm' => 25.0,
                'area_rai' => 15_625,
                'risk_level' => 'critical',
                'coordinates' => [
                    [19.1300, 99.8800],
                    [19.1700, 99.9400],
                    [19.1400, 99.9900],
                    [19.0900, 99.9700],
                    [19.0700, 99.9100],
                    [19.1000, 99.8600],
                ],
            ],
            [
                'zone_id' => 'GISTDA_N_NAN_01',
                'province' => 'น่าน',
                'region' => 'north',
                'name' => 'น้ำท่วมลุ่มน้ำน่าน - เมืองน่าน',
                'area_sqkm' => 31.2,
                'area_rai' => 19_500,
                'risk_level' => 'critical',
                'coordinates' => [
                    [18.8000, 100.7600],
                    [18.8500, 100.8100],
                    [18.8200, 100.8700],
                    [18.7700, 100.8500],
                    [18.7500, 100.7900],
                    [18.7700, 100.7500],
                ],
            ],
            [
                'zone_id' => 'GISTDA_N_CH_01',
                'province' => 'เชียงราย',
                'region' => 'north',
                'name' => 'น้ำท่วมลุ่มน้ำกก เชียงราย - แม่จัน',
                'area_sqkm' => 42.5,
                'area_rai' => 26_562,
                'risk_level' => 'critical',
                'coordinates' => [
                    [19.9800, 99.8200],
                    [20.0300, 99.8900],
                    [20.0000, 99.9500],
                    [19.9400, 99.9200],
                    [19.9200, 99.8600],
                    [19.9500, 99.8100],
                ],
            ],

            // ============================================
            // ภาคอีสาน (NORTHEAST)
            // ============================================
            [
                'zone_id' => 'GISTDA_NE_UD_01',
                'province' => 'อุดรธานี',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำสงคราม อุดรธานี - หนองหาน',
                'area_sqkm' => 55.6,
                'area_rai' => 34_750,
                'risk_level' => 'critical',
                'coordinates' => [
                    [17.3200, 102.9800],
                    [17.3800, 103.0400],
                    [17.3500, 103.1000],
                    [17.2900, 103.0800],
                    [17.2600, 103.0200],
                    [17.2900, 102.9600],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_NP_01',
                'province' => 'นครพนม',
                'region' => 'northeast',
                'name' => 'น้ำท่วมริมโขง นครพนม (เมือง-ท่าอุเทน)',
                'area_sqkm' => 48.2,
                'area_rai' => 30_125,
                'risk_level' => 'critical',
                'coordinates' => [
                    [17.3800, 104.7400],
                    [17.4300, 104.7900],
                    [17.4000, 104.8400],
                    [17.3400, 104.8200],
                    [17.3100, 104.7600],
                    [17.3400, 104.7200],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_SK_01',
                'province' => 'สกลนคร',
                'region' => 'northeast',
                'name' => 'น้ำท่วมหนองหาร สกลนคร',
                'area_sqkm' => 68.0,
                'area_rai' => 42_500,
                'risk_level' => 'critical',
                'coordinates' => [
                    [17.1800, 103.9000],
                    [17.2400, 103.9700],
                    [17.2000, 104.0300],
                    [17.1300, 104.0100],
                    [17.1000, 103.9400],
                    [17.1300, 103.8900],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_ROI_01',
                'province' => 'ร้อยเอ็ด',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำชี ร้อยเอ็ด (ธวัชบุรี-เสลภูมิ)',
                'area_sqkm' => 19.97,
                'area_rai' => 12_480,
                'risk_level' => 'critical',
                'coordinates' => [
                    [16.0350, 103.7300],
                    [16.0680, 103.7750],
                    [16.0520, 103.8200],
                    [16.0150, 103.8450],
                    [15.9900, 103.7900],
                    [16.0100, 103.7400],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_KK_01',
                'province' => 'ขอนแก่น',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำชี ขอนแก่น (ชุมแพ-น้ำพอง)',
                'area_sqkm' => 44.8,
                'area_rai' => 28_000,
                'risk_level' => 'high',
                'coordinates' => [
                    [16.4300, 102.7800],
                    [16.4900, 102.8500],
                    [16.4500, 102.9100],
                    [16.3800, 102.8800],
                    [16.3600, 102.8100],
                    [16.3900, 102.7600],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_MKM_01',
                'province' => 'มุกดาหาร',
                'region' => 'northeast',
                'name' => 'น้ำท่วมริมโขง มุกดาหาร - คำชะอี',
                'area_sqkm' => 32.5,
                'area_rai' => 20_312,
                'risk_level' => 'high',
                'coordinates' => [
                    [16.5300, 104.6800],
                    [16.5800, 104.7400],
                    [16.5500, 104.8000],
                    [16.4900, 104.7700],
                    [16.4700, 104.7100],
                    [16.5000, 104.6600],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_BKN_01',
                'province' => 'บึงกาฬ',
                'region' => 'northeast',
                'name' => 'น้ำท่วมริมโขง บึงกาฬ (เมือง-ปากคาด)',
                'area_sqkm' => 60.1,
                'area_rai' => 37_562,
                'risk_level' => 'critical',
                'coordinates' => [
                    [18.3400, 103.5700],
                    [18.3900, 103.6300],
                    [18.3600, 103.6900],
                    [18.3000, 103.6600],
                    [18.2800, 103.6000],
                    [18.3100, 103.5500],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_LS_01',
                'province' => 'เลย',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำเลย วังสะพุง-เชียงคาน',
                'area_sqkm' => 27.8,
                'area_rai' => 17_375,
                'risk_level' => 'high',
                'coordinates' => [
                    [17.8800, 101.6500],
                    [17.9300, 101.7200],
                    [17.9000, 101.7800],
                    [17.8400, 101.7500],
                    [17.8200, 101.6800],
                    [17.8500, 101.6300],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_YST_01',
                'province' => 'ยโสธร',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำชีตอนล่าง ยโสธร (เมือง-ป่าติ้ว)',
                'area_sqkm' => 38.9,
                'area_rai' => 24_312,
                'risk_level' => 'critical',
                'coordinates' => [
                    [15.8400, 104.0800],
                    [15.8900, 104.1400],
                    [15.8600, 104.2000],
                    [15.8000, 104.1700],
                    [15.7800, 104.1100],
                    [15.8100, 104.0600],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_AMN_01',
                'province' => 'อำนาจเจริญ',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำโขง-มูล อำนาจเจริญ',
                'area_sqkm' => 22.3,
                'area_rai' => 13_937,
                'risk_level' => 'high',
                'coordinates' => [
                    [15.8800, 104.5600],
                    [15.9200, 104.6200],
                    [15.9000, 104.6800],
                    [15.8400, 104.6500],
                    [15.8200, 104.5900],
                    [15.8500, 104.5400],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_UBN_01',
                'province' => 'อุบลราชธานี',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำมูล-ชี อุบลราชธานี (เมือง-วารินชำราบ)',
                'area_sqkm' => 85.4,
                'area_rai' => 53_375,
                'risk_level' => 'critical',
                'coordinates' => [
                    [15.2300, 104.8000],
                    [15.2900, 104.8700],
                    [15.2600, 104.9300],
                    [15.2000, 104.9000],
                    [15.1700, 104.8400],
                    [15.2000, 104.7900],
                ],
            ],
            [
                'zone_id' => 'GISTDA_NE_MH_01',
                'province' => 'มหาสารคาม',
                'region' => 'northeast',
                'name' => 'น้ำท่วมลุ่มน้ำชี มหาสารคาม (เมือง-กันทรวิชัย)',
                'area_sqkm' => 41.2,
                'area_rai' => 25_750,
                'risk_level' => 'high',
                'coordinates' => [
                    [16.1900, 103.2600],
                    [16.2400, 103.3200],
                    [16.2100, 103.3800],
                    [16.1500, 103.3500],
                    [16.1300, 103.2900],
                    [16.1600, 103.2400],
                ],
            ],

            // ============================================
            // ภาคกลาง (CENTRAL)
            // ============================================
            [
                'zone_id' => 'GISTDA_C_NK_01',
                'province' => 'นครสวรรค์',
                'region' => 'central',
                'name' => 'น้ำท่วมลุ่มน้ำเจ้าพระยา นครสวรรค์ (เมือง-ชุมแสง)',
                'area_sqkm' => 75.3,
                'area_rai' => 47_062,
                'risk_level' => 'critical',
                'coordinates' => [
                    [15.7100, 100.0900],
                    [15.7700, 100.1600],
                    [15.7300, 100.2200],
                    [15.6700, 100.1900],
                    [15.6400, 100.1300],
                    [15.6800, 100.0700],
                ],
            ],
            [
                'zone_id' => 'GISTDA_C_AY_01',
                'province' => 'พระนครศรีอยุธยา',
                'region' => 'central',
                'name' => 'น้ำท่วมพื้นที่เกษตร พระนครศรีอยุธยา (บางไทร-บางปะอิน)',
                'area_sqkm' => 62.8,
                'area_rai' => 39_250,
                'risk_level' => 'high',
                'coordinates' => [
                    [14.3600, 100.5000],
                    [14.4100, 100.5600],
                    [14.3800, 100.6200],
                    [14.3200, 100.5900],
                    [14.3000, 100.5300],
                    [14.3300, 100.4800],
                ],
            ],
            [
                'zone_id' => 'GISTDA_C_SB_01',
                'province' => 'สุพรรณบุรี',
                'region' => 'central',
                'name' => 'น้ำท่วมลุ่มน้ำท่าจีน สุพรรณบุรี (อู่ทอง-เดิมบางนางบวช)',
                'area_sqkm' => 58.9,
                'area_rai' => 36_812,
                'risk_level' => 'high',
                'coordinates' => [
                    [14.5600, 99.9400],
                    [14.6100, 100.0100],
                    [14.5800, 100.0700],
                    [14.5200, 100.0400],
                    [14.5000, 99.9700],
                    [14.5300, 99.9200],
                ],
            ],
            [
                'zone_id' => 'GISTDA_C_CB_01',
                'province' => 'ชัยนาท',
                'region' => 'central',
                'name' => 'น้ำท่วมลุ่มน้ำเจ้าพระยา ชัยนาท (เมือง-สรรคบุรี)',
                'area_sqkm' => 45.6,
                'area_rai' => 28_500,
                'risk_level' => 'high',
                'coordinates' => [
                    [15.1700, 100.1000],
                    [15.2200, 100.1600],
                    [15.2000, 100.2200],
                    [15.1400, 100.2000],
                    [15.1100, 100.1400],
                    [15.1400, 100.0900],
                ],
            ],
            [
                'zone_id' => 'GISTDA_C_SNG_01',
                'province' => 'สิงห์บุรี',
                'region' => 'central',
                'name' => 'น้ำท่วมสิงห์บุรี - อ่างทอง ลุ่มน้ำเจ้าพระยา',
                'area_sqkm' => 33.4,
                'area_rai' => 20_875,
                'risk_level' => 'medium',
                'coordinates' => [
                    [14.8800, 100.3800],
                    [14.9200, 100.4400],
                    [14.9000, 100.5000],
                    [14.8400, 100.4700],
                    [14.8200, 100.4100],
                    [14.8500, 100.3700],
                ],
            ],
            [
                'zone_id' => 'GISTDA_C_NK2_01',
                'province' => 'นครนายก',
                'region' => 'central',
                'name' => 'น้ำท่วมลุ่มน้ำนครนายก (เมือง-บ้านนา)',
                'area_sqkm' => 28.7,
                'area_rai' => 17_937,
                'risk_level' => 'medium',
                'coordinates' => [
                    [14.2600, 101.1800],
                    [14.3000, 101.2400],
                    [14.2700, 101.3000],
                    [14.2200, 101.2700],
                    [14.2000, 101.2100],
                    [14.2300, 101.1600],
                ],
            ],

            // ============================================
            // ภาคใต้ (SOUTH)
            // ============================================
            [
                'zone_id' => 'GISTDA_S_NRT_01',
                'province' => 'นครศรีธรรมราช',
                'region' => 'south',
                'name' => 'น้ำท่วมพื้นที่ลุ่มต่ำ นครศรีธรรมราช (ชะอวด-เชียรใหญ่)',
                'area_sqkm' => 92.1,
                'area_rai' => 57_562,
                'risk_level' => 'critical',
                'coordinates' => [
                    [8.3100, 100.0300],
                    [8.3700, 100.1000],
                    [8.3400, 100.1600],
                    [8.2800, 100.1300],
                    [8.2500, 100.0700],
                    [8.2800, 100.0100],
                ],
            ],
            [
                'zone_id' => 'GISTDA_S_SNG_01',
                'province' => 'สงขลา',
                'region' => 'south',
                'name' => 'น้ำท่วมลุ่มน้ำทะเลสาบสงขลา (รัตภูมิ-หาดใหญ่)',
                'area_sqkm' => 65.8,
                'area_rai' => 41_125,
                'risk_level' => 'critical',
                'coordinates' => [
                    [7.0700, 100.4200],
                    [7.1200, 100.4900],
                    [7.0900, 100.5500],
                    [7.0300, 100.5200],
                    [7.0100, 100.4600],
                    [7.0400, 100.4000],
                ],
            ],
            [
                'zone_id' => 'GISTDA_S_PTN_01',
                'province' => 'พัทลุง',
                'region' => 'south',
                'name' => 'น้ำท่วมลุ่มน้ำพัทลุง ทะเลน้อย-ป่าพะยอม',
                'area_sqkm' => 49.5,
                'area_rai' => 30_937,
                'risk_level' => 'high',
                'coordinates' => [
                    [7.6200, 100.1100],
                    [7.6700, 100.1700],
                    [7.6400, 100.2300],
                    [7.5800, 100.2100],
                    [7.5600, 100.1500],
                    [7.5900, 100.0900],
                ],
            ],
            [
                'zone_id' => 'GISTDA_S_STN_01',
                'province' => 'สุราษฎร์ธานี',
                'region' => 'south',
                'name' => 'น้ำท่วมลุ่มน้ำตาปี สุราษฎร์ธานี (พุนพิน-เมือง)',
                'area_sqkm' => 44.2,
                'area_rai' => 27_625,
                'risk_level' => 'high',
                'coordinates' => [
                    [9.1200, 99.2600],
                    [9.1700, 99.3200],
                    [9.1400, 99.3800],
                    [9.0800, 99.3600],
                    [9.0600, 99.3000],
                    [9.0900, 99.2500],
                ],
            ],

            // ============================================
            // ภาคตะวันออก (EAST)
            // ============================================
            [
                'zone_id' => 'GISTDA_E_PBR_01',
                'province' => 'ปราจีนบุรี',
                'region' => 'east',
                'name' => 'น้ำท่วมลุ่มน้ำปราจีนบุรี (กบินทร์บุรี-เมือง)',
                'area_sqkm' => 38.6,
                'area_rai' => 24_125,
                'risk_level' => 'high',
                'coordinates' => [
                    [14.0800, 101.3900],
                    [14.1300, 101.4600],
                    [14.1000, 101.5200],
                    [14.0400, 101.4900],
                    [14.0200, 101.4300],
                    [14.0500, 101.3800],
                ],
            ],
            [
                'zone_id' => 'GISTDA_E_FCB_01',
                'province' => 'ฉะเชิงเทรา',
                'region' => 'east',
                'name' => 'น้ำท่วมลุ่มน้ำบางปะกง ฉะเชิงเทรา (บางน้ำเปรี้ยว-เมือง)',
                'area_sqkm' => 55.1,
                'area_rai' => 34_437,
                'risk_level' => 'critical',
                'coordinates' => [
                    [13.7100, 101.0200],
                    [13.7600, 101.0800],
                    [13.7300, 101.1400],
                    [13.6700, 101.1200],
                    [13.6500, 101.0600],
                    [13.6800, 101.0100],
                ],
            ],
        ];
    }
}
