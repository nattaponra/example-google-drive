
### 1. สร้างไฟล์ credentials.json 
- โดยเข้าไปที่ https://developers.google.com/drive/api/v3/quickstart/php
- คลิ๊กที่ "ENABLE THE DRIVE API" > "Download client configuration" 
- บันทึกไฟล์ credentials.json ลงในโฟลเดอร์

### 2. ขอสิทธิ์การเข้าถึง Google Drive และทดลอง Upload file ภาพ
- รันไฟล์ php  
```
php backend/auth-upload-file.php 
```
เปิดลิ้งนี้เพื่อขอสิทธิ์:
https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id=622466009296-k5mguu4lvieg7t217cffsu3i6j7jom8j.apps.googleusercontent.com&redirect_uri=urn%3Aietf%3Awg%3Aoauth%3A2.0%3Aoob&state&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive&prompt=select_account%20consent
กรอก code ที่ได้เพื่อยืนยัน: 


- นำ code ที่ได้จากการขอสิทธิ์มา กรอกลง console แล้ว Enter เพื่อทำการแลก Access Token และ Refresh Token ไปใช้ในการ upload file.



### ตัวอย่าง Code
```php
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Drive API PHP Quickstart');
    // DRIVE คือ สิทธิ์สูงสุด เพื่ม ลบ อ่าน เขียนไฟล์
    // อ่านรายละเอียดได้ที่ --> https://github.com/googleapis/google-api-php-client-services/blob/master/src/Google/Service/Drive.php
    $client->setScopes(Google_Service_Drive::DRIVE);
    
    //credentials สร้าง app กากๆขึ้นมา
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    //ครั้งแรกจะขอสิทธิ์ก่อน
    //โดยจะใช้ไฟล์ token.json ในการเก็บ Access , Refresh Token
  
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) { //ถ้ามีไฟล์ จะโหลดเอา tokenมา set
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }
    //ตรวจสอบว่า Access Token หมดอายุหรือยัง?
    if ($client->isAccessTokenExpired()) {
      
        //ถ้าหมดให้ลอง Refresh  ใหม่ดู
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else { 
            // ขอสิทธิ์จาก user ใหม่
            $authUrl = $client->createAuthUrl();
            printf("เปิดลิ้งนี้เพื่อขอสิทธิ์:\n%s\n", $authUrl);
            print 'กรอก code ที่ได้เพื่อยืนยัน: ';
            $authCode = trim(fgets(STDIN));
            // เอา code ที่ได้มาแลกเป็น Access Token และ Refresh Token เก็บไว้ใช้ครั้งต่อไป โดยไม่ต้องขอสิทธิ์อีก
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);
            // หาก Error
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // เก็บ Access Token และ Refresh Token ลงในไฟล์ token.json
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}
// สร้าง client object เพื่อเอาไปใช้งาน กับ Service ของ Google ได้หลายๆตัว
$client = getClient();
//แต่เราจะใช้ใน Service ของ Google Drive
$service = new Google_Service_Drive($client);
//สร้าง MetaData ของไฟล์ โดยกำหนดชื่อที่เป็น 'file1.jpg'
 $fileMetadata = new Google_Service_Drive_DriveFile([
    'name' => 'file1.jpg'
 ]);
 // โหลด connect ของไฟล์จาก example-files
 $content = file_get_contents('example-files/file1.jpg');
// สร้างไฟล์ขึ้นมาใน Drive (Upload file)
 $file = $service->files->create($fileMetadata, array(
    'data' => $content,
    'mimeType' => 'image/jpeg',
    'uploadType' => 'multipart',
    'fields' => 'id'));
//สุดท้ายเราจะได้เป็น ID กลับมา เพื่อนำ ID นี้ไปใช้ในฟังก์ชันงานอื่น เช่น แก้ไข  ลบ เป็นต้น    
printf("File ID: %s\n", $file->id);
```