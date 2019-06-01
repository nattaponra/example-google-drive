
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
