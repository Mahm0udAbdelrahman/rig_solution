<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralInfo\EquipmentControlledList;
use Carbon\Carbon;

class EquipmentControlledListSeeder extends Seeder
{
    /**
     * Equipment Controlled List (RS-IMS-P10-F01, last update 06-01-25), one item per line:
     * Description | Internal Code | Manufacturer | Model / Type | Capacity / Range | Serial Number | Date into Service |
     * Interval | Calibration Date | Calibration Due Date | Calibrated By | Re-calibration Alarm | Location | Status | Date Removed / Remarks
     *
     * Date into Service is dd-Mon-yyyy, calibration dates are dd-mm-yy. N/A, blank and the sheet's empty-date value 29-12-00 are stored as no date.
     */
    protected $items = <<<'LIST'
Water Bag|RS-NL-WB-72-01|Seaflex|WB35|35-Ton|2372|21-Mar-2011|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-08-02|Water weights|WB35|35-Ton|WB3508|16-Mar-2011|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-19-03|Seaflex|N/A|5-Ton|40519|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-44-04|Seaflex|N/A|2-Ton|42644|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-48-05|Seaflex|N/A|2-Ton|42648|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-49-06|Seaflex|N/A|2-Ton|42649|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-08-07|Seaflex|N/A|1-Ton|41508|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Water Bag|RS-NL-WB-10-08|Seaflex|N/A|1-Ton|41510|01-Nov-2021|Pre-Use|N/A|N/A|N/A|Calibrated|Store|Active|
Load Cell|RS-NL-LC-10-09|Dynafore|JCM|10-Ton|2010-242A|01-Jul-2017|Pre-Use|15-12-24|14-12-25|OMEGA|Re-Calibrate|Store|Active|
Load Cell|RS-NL-LC-11-10|DILLON|EDXTREME|20-Ton|DEDX2700011|01-May-2020|Pre-Use|21-11-24|20-11-25|OMEGA|Re-Calibrate|Store|Active|
Load Cell|RS-NL-LC-56-11|DILLON|EDXTREME|20-Ton|DEDX2700056|01-May-2020|Pre-Use|14-01-24|12-01-25|OMEGA|Re-Calibrate|Store|Active|
Load Cell|RS-NL-LC-42-12|Water weights|Water weights|25-Ton|642|01-Jan-2018|Pre-Use|19-07-22|18-07-23|First|Re-Calibrate|Store|Under Maintenance|
Load Cell|RS-NL-LC-48-94|JCM Load|T24-HS-SP-JCM|25-Ton|13448|25-Feb-2024|Pre-Use/Annual|05-06-24|04-06-25|NIS|Re-Calibrate|Store|Active|
Load Cell|RS-NL-LC-13-13|Straightpoint|Straightpoint|50-Ton|16713|01-Jan-2018|Pre-Use/Annual|30-05-23|28-05-24|NIS|Re-Calibrate|Store|Under Calibration|
Load Cell|RS-NL-LC-12-95|ACCUWAY|T24-HS-FL|50-Ton|1169/12|25-Feb-2024|Pre-Use/Annual|13-05-24|12-05-25|OMEGA|Re-Calibrate|Store|Active|
Load Cell|RS-NL-LC-35-96|JCM Load|T24-HS-SP-JCM|75-Ton|13535|25-Feb-2024|Pre-Use/Annual|14-05-24|13-05-25|NIS|Re-Calibrate|Store|Active|
Flow Detector UT|RS-NL-FD-05-14|India tools|ITI-1700|N/A|140010005|01-Oct-2018|Pre-Use/Annual|22-07-23|20-07-24|OMEGA|Re-Calibrate|Store|Under Calibration|
Flow Detector UT|RS-NL-FD-02-15|Olympos|E600|N/A|150848702|01-Mar-2023|Pre-Use/Annual|25-08-24|24-08-25|OMEGA|Re-Calibrate|Store|Active|
UT STD Block|RS-NL-FD-61-65|II W2/20|V2|N/A|1209261|01-Mar-2023|Pre-Use/Annual|22-05-23|20-05-24|NIS|Re-Calibrate|Store|Under Calibration|need check date
Flow Detector UT|RS-NL-FD-00-77|SONATEST|D-10 +|N/A|1009541|01-May-2024|Pre-Use/Annual|26-05-24|25-05-25|NIS|Re-Calibrate|Store|Active|
UT STD Block|RS-NL-FD-00-78|BCB|V-1|N/A|1009541|01-May-2024|Pre-Use/Annual|26-05-24|25-05-25|NIS|Re-Calibrate|Store|Active|
UT STD Block|RS-NL-FD-00-79|BCB|V2|N/A|1009541|01-May-2024|Pre-Use/Annual|26-05-24|25-05-25|NIS|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-19-16|SONATEST|T-GAGE-IV|N/A|43519|N/A|Pre-Use/Annual|03-06-24|02-06-25|NIS|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-25-17|SIUI|CTS/30B|N/A|55331315025|N/A|Pre-Use/Annual|29-12-00||First|Re-Calibrate|Store|Out of Service|
UT Thickness Gauge|RS-NL-UT-50-18|GE Inspection|DM-4|N/A|35250|N/A|Pre-Use/Annual|17-04-24|16-04-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-55-19|GE Inspection|DM-4|N/A|16855|N/A|Pre-Use/Annual|26-06-22|25-06-23|OMEGA|Re-Calibrate|Store|Out of Service|
UT Thickness Gauge|RS-NL-UT-VO-20|Krautkramer|DM-4e|N/A|009PVO|N/A|Pre-Use/Annual|21-01-24|19-01-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-02-21|Magna Flux|MT-21|N/A|RSE-TH02|N/A|Pre-Use/Annual|17-01-24|15-01-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-85-22|GE Inspection|DM5E|N/A|DM5EG1710387|N/A|Pre-Use/Annual|22-05-23|20-05-24|NIS|Re-Calibrate|Store|Under Maintenance|out of service
UT Thickness Gauge|RS-NL-UT-08-76|AND|AD-3253|N/A|K1401508|N/A|Pre-Use/Annual|29-02-24|27-02-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-81-80|DAKOTA|MX-1|N/A|11981|N/A|Pre-Use/Annual|31-07-24|30-07-25|OMEGA|Re-Calibrate|Store|Active|
Step Block|RS-NL-SB-20-81|N/A|4340FE|N/A|09-2920|N/A|Pre-Use/Annual|20-05-24|19-05-25|NIS|Re-Calibrate|Store|Active|Check
UT Thickness Gauge|RS-NL-UT-73-119|BENETCH|GM100|N/A|2511873|01-Oct-2024|Pre-Use/Annual|21-11-24|20-11-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-17-106|Checkline|TI-25M-MMX|N/A|5817|N/A|Pre-Use/Annual|10-01-24|08-01-25|OMEGA|Re-Calibrate|Store|Active|
UT Thickness Gauge|RS-NL-UT-24-120|India Tools|ITI-1600|N/A|8120024|15-Dec-2024|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|New|
UT Thickness Gauge|RS-NL-UT-40-121|India tools|MT180|N/A|MT0124101240|15-Dec-2024|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|New|
Hardness Tester|RS-NL-HT-01-23|DHT-2010|DHT-2010|N/A|DHT2010-1|N/A|Pre-Use/Annual|23-09-24|22-09-25|OMEGA|Re-Calibrate|Store|Active|
Yoke|RS-NL-PY-01-24|N/A|N/A|N/A|1001|N/A|Pre-Use/Annual|07-03-24|06-03-25|OMEGA|Re-Calibrate|Mohamed Hegazy|Active|
Yoke|RS-NL-PY-02-25|N/A|N/A|N/A|1002|N/A|Pre-Use/Annual|07-03-24|06-03-25|OMEGA|Re-Calibrate|Mhd-Abd Salam|Active|
Yoke|RS-NL-PY-03-26|N/A|N/A|N/A|1003|N/A|Pre-Use/Annual|17-01-24|15-01-25|OMEGA|Re-Calibrate|Osama Ismail|Active|
Yoke|RS-NL-PY-04-27|N/A|N/A|N/A|1004|N/A|Pre-Use/Annual|25-03-24|24-03-25|OMEGA|Re-Calibrate|Islam Mohamed|Active|
Yoke|RS-NL-PY-05-28|N/A|N/A|N/A|1005|N/A|Pre-Use/Annual|05-02-22|04-02-23|OMEGA|Re-Calibrate|Store|Out of Service|
Yoke|RS-NL-PY-06-29|N/A|N/A|N/A|1006|N/A|Pre-Use/Annual|20-09-23|18-09-24|OMEGA|Re-Calibrate|Store|Under Calibration|Check
Yoke|RS-NL-PY-01-30|N/A|N/A|N/A|2001|N/A|Pre-Use/Annual|21-01-24|19-01-25|OMEGA|Re-Calibrate|Mostafa Taher|Active|
Yoke|RS-NL-PY-02-31|N/A|N/A|N/A|2002|N/A|Pre-Use/Annual|15-12-24|14-12-25|OMEGA|Re-Calibrate|Kamel Okash|Active|
Yoke|RS-NL-PY-03-32|N/A|N/A|N/A|2003|N/A|Pre-Use/Annual|15-12-24|14-12-25|OMEGA|Re-Calibrate|Omar Selim|Active|
Yoke|RS-NL-PY-04-33|N/A|N/A|N/A|2004|N/A|Pre-Use/Annual|22-09-24|21-09-25|OMEGA|Re-Calibrate|Ahmed Abbass|Active|
Yoke|RS-NL-PY-05-34|N/A|N/A|N/A|2005|N/A|Pre-Use/Annual|29-12-00||First|Re-Calibrate|Mozafer|Out of Service|Check
Yoke|RS-NL-PY-06-107|N/A|N/A|N/A|2006|N/A|Pre-Use/Annual|||First|Re-Calibrate||Out of Service|Check
Yoke|RS-NL-PY-01-35|PARKER|PM-50|N/A|3001|N/A|Pre-Use/Annual|08-05-24|07-05-25|NIS|Re-Calibrate|Store|Active|
Yoke|RS-NL-PY-02-36|PARKER|PM-50|N/A|3002|N/A|Pre-Use/Annual|10-01-24|08-01-25|OMEGA|Re-Calibrate|Store|Active|
Yoke|RS-NL-PY-03-50|PARKER|PM-50|N/A|3003|N/A|Pre-Use/Annual|08-08-24|07-08-25|NIS|Re-Calibrate|Store|Active|
Yoke|RS-NL-PY-01-100|N/A|N/A|N/A|4001|N/A|Pre-Use/Annual|28-04-24|27-04-25|OMEGA|Re-Calibrate|Ibrahim|Active|
Yoke|RS-NL-PY-02-103|N/A|N/A|N/A|4002|N/A|Pre-Use/Annual|27-05-24|26-05-25|OMEGA|Re-Calibrate|Mahmoud Naeim|Active|
Yoke|RS-NL-PY-03-104|N/A|N/A|N/A|4003|N/A|Pre-Use/Annual|27-05-24|26-05-25|OMEGA|Re-Calibrate|Store|Active|
Yoke|RS-NL-EY-08-37|MAGTEST|MY-2|N/A|1909108|N/A|Pre-Use/Annual|08-08-24|07-08-25|NIS|Re-Calibrate|Store|Active|
Yoke|RS-NL-EY-10-38|Johnson & Allen|N/A|N/A|D010|N/A|Pre-Use/Annual|31-12-24|30-12-25|OMEGA|Re-Calibrate|GESCO|Active|
Test Block|RS-NL-TB-01-86|N/A|18 KG|18 KG|TB01|N/A|Pre-Use/Annual|13-08-24|12-08-25|NIS|Re-Calibrate|Lab|Active|
Test Block|RS-NL-TB-02-105|N/A|4.5 KG|4.5 KG|TB 02|N/A|Pre-Use/Annual|13-08-24|12-08-25|NIS|Re-Calibrate|Lab|Active|
Shooting Coil|RS-NL-SC-02-39|SALVATOR|Coil 8"|N/A|SC180402|N/A|Pre-Use/Annual|31-07-24|30-07-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|RS-NL-SC-91-40|VAPCO|Coil 8"|N/A|EM491|N/A|Pre-Use/Annual|05-09-24|04-09-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|SC-NL-SC-01-41|VAPCO|COIL 8"|N/A|EM501|N/A|Pre-Use/Annual|31-07-24|30-07-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|SC-NL-SC-92-42|VAPCO|COIL 14"|N/A|EM492|N/A|Pre-Use/Annual|12-08-24|11-08-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|SC-NL-SC-02-43|VAPCO|COIL 14"|N/A|EM502|N/A|Pre-Use/Annual|31-07-24|30-07-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|SC-NL-SC-00-44|VAPCO|Coil 8"|N/A|CSRS001|N/A|Pre-Use/Annual|05-09-24|04-09-25|OMEGA|Re-Calibrate|Store|Active|
Shooting Coil|SC-NL-SC-00-45|VAPCO|COIL 12"|N/A|CSRS002|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Out of Service|Check
Ultra Violet Lamp|RS-NL-UV-47-46|N/A|N/A|N/A|BLL-18047(BUV01)|N/A|Pre-Use/Annual|19-07-23|17-07-24||Re-Calibrate|Store|Out of Service|Check
Ultra Violet Lamp|RS-NL-UV-42-47|Electroline|BIB-150P|N/A|1640642|N/A|Pre-Use/Annual|31-12-24|30-12-25|OMEGA|Re-Calibrate|Gesco|Active|
Ultra Violet Lamp|RS-NL-UV-31-48|SPECTROLINE|SB-100P/F|N/A|1453731|N/A|Pre-Use/Annual|27-06-22|26-06-23||Re-Calibrate|Store|Out of Service|Check
Ultra Violet Lamp|RS-NL-UV-59-82|SPECTROLINE|365NM|N/A|52419|N/A|Pre-Use/Annual|24-09-24|23-09-25|OMEGA|Re-Calibrate|Store|Active|
Ultra Violet Lamp|RS-NL-UV-59-98|SPECTROLINE|365NM|N/A|23659|N/A|Pre-Use/Annual|06-08-24|05-08-25|OMEGA|Re-Calibrate|Store|Active|
Ultra Violet Lamp|RS-NL-UV-01-49|MR-CHEMIE|MR-CHEMIE|N/A|RS-UV-01|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Out of Service|Check
Ultra Violet Lamp|RS-NL-UV-02-108|N/A|UV Lamp|N/A|RS-UV-02|01-Dec-2024|Pre-Use/Annual|21-11-24|20-11-25|OMEGA|Re-Calibrate|Store|Active|
Ultra Violet Lamp|RS-NL-UV-03-109|N/A|UV Lamp|N/A|RS-UV-03|01-Dec-2024|Pre-Use/Annual||||Re-Calibrate|Store|Under Calibration|
Ultra Violet Lamp|RS-NL-UV-04-110|N/A|UV Lamp|N/A|RS-UV-04|01-Dec-2024|Pre-Use/Annual||||Re-Calibrate|Store|Under Calibration|
Ultra Violet Lamp|RS-NL-UV-05-111|N/A|UV Lamp|N/A|RS-UV-05|01-Dec-2024|Pre-Use/Annual|15-12-24|14-12-25|OMEGA|Re-Calibrate|Store|Active|
Ultra Violet Lamp|RS-NL-UV-06-112|N/A|UV Lamp|N/A|RS-UV-06|01-Dec-2024|Pre-Use/Annual|15-12-24|14-12-25|OMEGA|Re-Calibrate|Store|Active|
Ultra Violet Lamp|RS-NL-UV-07-113|N/A|UV Lamp|N/A|RS-UV-07|01-Dec-2024|Pre-Use/Annual|25-12-24|24-12-25|OMEGA|Re-Calibrate|Store|Active|
UV Meter|RS-NL-BM-24-51|Magnaflux|UV-A Black Light|40924|40924|16-Jan-2012|Pre-Use/Annual|17-05-23|15-05-24|NIS|Re-Calibrate|Store|Under Calibration|
UV Meter|RS-NL-BM-12-97|Magnaflux|UV-A Black Light|40912|40912|04-Jan-2012|Pre-Use/Annual|23-05-24|22-05-25|NIS|Re-Calibrate|Store|Active|
Pressure Gauge|RS-NL-PG-G2-93|Enerpac|GF-813B|N/A|PG-2 (7065677)|N/A|Pre-Use/Annual|20-01-24|18-01-25|RSE|Re-Calibrate|Store|Active|
Jack|RS-NL-HJ-01-52|Enerpac|RCH202|20-Ton|202R01|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Jack|RS-NL-HJ-02-53|Enerpac|RCH302|30-Ton|302R02|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Jack|RS-NL-HJ-03-54|Enerpac|RCH302|30-Ton|302R03|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Jack|RS-NL-HJ-03-55|||100-Ton||N/A|||||Re-Calibrate|||
Pad Eye Tester|RS-NL--PT-01-56|N/A|N/A|30-Ton|RSE-30-01|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Pad Eye Tester|RS-NL-HC-02-57|Enerpac|RCH302|30-Ton|RSE-30-02|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Hand Pump|RS-NL-HP-01-58|Enerpac|P141|10000 psi|HP-10001|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
Hand Pump|RS-NL-HC-02-59|Enerpac|P392|10000 psi|HP-10002|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
EMI Scope|RS-NL-EM-01-60|Pipe-Tech Sys.|Pipe-Tech Sys.|2 3/8- 5 1/2"|RS-EMI-001|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
EMI Coil|RS-NL-CO-01-61|||8"|CO-201|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
EMI Buggy|RS-NL-B-01-62||||B-101|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
EMI Scope|RS-NL-EM-02-63|Pipe-Tech Sys.|Pipe-Tech Sys.|2 3/8- 5 1/2"|RS-EMI-002|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
EMI Coil|RS-NL-CO-02-114|||8"|CO-202|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
EMI Buggy|RS-NL-B-02-117||||B-102|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
EMI Scope|RS-NL-EM-03-64|Pipe-Tech Sys.|Pipe-Tech Sys.|2 3/8- 5 1/2"|RSE-EMI-003|N/A|Pre-Use/Annual|N/A|N/A|N/A|Calibrated|Store|Active|
EMI Coil|RS-NL-CO-03-115|||8"|CO-203|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
EMI Buggy|RS-NL-B-03-118||||B-103|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Active|
Laser Meter|RS-NL-LM-57-99|Bosch|GLM-20|20-M|26414657|N/A|Pre-Use/Annual|08-06-23|06-06-24|NIS|Re-Calibrate|Store|Under Calibration|
Laser Meter|RS-NL-LM-00-83|Bosch|GLM-40|40-M|223215800|N/A|Pre-Use/Annual|08-06-23|06-06-24|NIS|Re-Calibrate|Store|Under Calibration|
Drillco Ruler|RS-NL-DR-13-84|New-Tech|N/A||DR-13|N/A|Pre-Use/Annual|08-05-24|07-05-25|NIS|Re-Calibrate|Store|Active|
Ruler|RS-NL-DR-14-85|Product Engineering|402-012||DR-14(402-012)|N/A|Pre-Use/Annual|08-05-24|07-05-25|NIS|Re-Calibrate|Store|Active|
Vernier Caliper|RS-NL-VC-15-87|APT|N/A||DR20|N/A|Pre-Use/Annual|14-05-24|13-05-25|NIS|Re-Calibrate|Store|Active|
Welding Gauge|RS-NL-WG-17-89|CQEG|N/A||DR17|N/A|Pre-Use/Annual|14-05-24|13-05-25|NIS|Re-Calibrate|Store|Active|
Welding Gauge|RS-NL-WG-18-90|CQEG|N/A||DR18|N/A|Pre-Use/Annual|14-05-24|13-05-25|NIS|Re-Calibrate|Store|Active|
Thermometer|RS-NL-TI-43-92|Crown|CT44037||21011102343|N/A|Pre-Use/Annual|21-11-23|19-11-24||Re-Calibrate|Store|Under Calibration|
Gauge Block Set|RS-CL-GB-23-75|N/A|N/A||23|N/A|Pre-Use/Annual|29-12-00|||Re-Calibrate|Store|Under Calibration|
Hyd. Calibrator|RS-CL-HC-46-66|Fluke|PL-P5514-2959||74746|N/A|Pre-Use/Annual|N/A|N/A|Fluke|Calibrated|Lab|Active|
Pressure Gauge|RS-CL-PG-16-67|Fluke|2700G-G70M|10000 psi|5087616|N/A|Pre-Use/Annual|27-11-24|26-11-25|NIS|Re-Calibrate|Lab|Active|
Pressure Gauge|RS-CL-PG-41-68|Fluke|2700G-G70M|10000 psi|4813041|N/A|Pre-Use/Annual|27-11-24|26-11-25|NIS|Re-Calibrate|Lab|Active|
Pressure Gauge|RS-CL-PG-86-69|PARKER|15000psi|15000 psi|8486|N/A|Pre-Use/Annual|09-01-23|08-01-24|HPS|Re-Calibrate|Lab|Under Calibration|
Pressure Pump|RS-CL-TP-01-70|Paker|N/A|20000 psi|0098|N/A|Pre-Use/Annual|09-01-23|08-01-24|HPS|Re-Calibrate|Lab|Under Calibration|
Chart Test Pressure|RS-CL-TP-01-71|Hydratron|N/A|20000 psi|350156/15|N/A|Pre-Use/Annual|09-01-23|08-01-24|HPS|Re-Calibrate|Lab|Under Calibration|
Torque Calibrator|RS-CL-TC-1-72|Checkline -AWS|41013-HYDP|6700 N/M|23866-1|N/A|Pre-Use/Annual|01-06-23|30-05-24||Re-Calibrate|Lab|Under Calibration|
Torque Wrench|RS-CL-TW-01-73|BAHCO|7455-1500|1500 N/M|1020600501|N/A|Pre-Use/Annual|31-12-24|30-12-25|NIS|Re-Calibrate|Lab|Active|
Temperature Device|RS-CL-TD-63-74|Thermo-Hygro|N/A|N/A|RS-CL-TD-63-74|N/A|Pre-Use/Annual|08-10-24|07-10-25|NIS|Re-Calibrate|Lab|Active|
Vibration Meter|RS-CL-VM-53-101|Balmacinc|235|N/A|1107053|N/A|Pre-Use/Annual|22-05-24|21-05-25|NIS|Re-Calibrate|Lab|Active|
Pressure Gauge|RS-CL-PG-41-102|ENERPAC|G2535L|10000 psi|5401456641|N/A|Pre-Use/Annual|13-08-24|12-08-25|NIS|Re-Calibrate|Lab|Active|
Torque Calibrator|RS-CL-TC-68-122|Norbar|43220|30-1500NM|63168|15-Dec-2024|Pre-Use/Annual|29-12-24|28-12-25|NIS|Re-Calibrate|Lab|Active|
Torque Calibrator|RS-CL-TC-88-123|Norbar|43220|30-1500NM|80088|15-Dec-2024|Pre-Use/Annual||||Re-Calibrate|Lab|Under Calibration|
LIST;

    public function run()
    {
        foreach (preg_split('/\R/', trim($this->items)) as $line) {
            [$description, $code, $manufacturer, $model, $capacity, $serial, $intoService,
                $interval, $calDate, $dueDate, $calibratedBy, $alarm, $location, $status, $removed] = array_map('trim', explode('|', $line));

            EquipmentControlledList::updateOrCreate(
                ['internal_code' => $code],
                [
                    'equipment_description' => $description,
                    'manufacturer' => $this->text($manufacturer),
                    'model_type' => $this->text($model),
                    'capacity_range' => $this->text($capacity),
                    'serial_number' => $this->text($serial),
                    'date_into_service' => $this->date($intoService, 'd-M-Y'),
                    'interval' => $this->text($interval),
                    'calibration_date' => $this->date($calDate, 'd-m-y'),
                    'calibration_due_date' => $this->date($dueDate, 'd-m-y'),
                    'calibrated_by' => $this->text($calibratedBy),
                    'recalibration_alarm' => $this->text($alarm),
                    'location_department' => $this->text($location),
                    // "New" items are in the store awaiting first calibration
                    'status' => $status === 'New' ? 'Active' : $this->text($status),
                    'date_removed_from_service' => $this->text($removed),
                    'notes' => $status === 'New' ? 'New' : null,
                ]
            );
        }
    }

    protected function text($value)
    {
        return $value === '' ? null : $value;
    }

    protected function date($value, $format)
    {
        if (in_array($value, ['', 'N/A', '29-12-00'], true)) {
            return null;
        }

        return Carbon::createFromFormat($format, $value)->format('Y-m-d');
    }
}
