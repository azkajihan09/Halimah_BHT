# 🚀 PANDUAN DEPLOY KE SERVER - SISTEM NOTELEN

## ✅ **CHECKLIST SEBELUM DEPLOY:**

### **1. Database Setup**
```sql
-- Buat database di server
CREATE DATABASE notelen_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import file SQL
-- Upload dan jalankan: create_notelen_database.sql
```

### **2. File Upload**
```
Upload semua file ke folder web server:
- /application/
- /assets/ 
- /system/
- /user_guide/
- index.php
- .htaccess
```

### **3. Configuration Files**

#### **A. Database Config** (`application/config/database.php`)
```php
// Update sesuai server
$db['default'] = array(
    'hostname' => 'localhost', // atau IP server
    'username' => 'your_db_user',
    'password' => 'your_db_password', 
    'database' => 'sipp_terbaru1', // database SIPP
);

$db['notelen_db'] = array(
    'hostname' => 'localhost',
    'username' => 'your_db_user', 
    'password' => 'your_db_password',
    'database' => 'notelen_system', // database notelen
);
```

#### **B. Base URL** (`application/config/config.php`)
✅ **SUDAH AUTO-DETECT** - tidak perlu ubah manual!

### **4. File Permissions (Linux/Unix)**
```bash
chmod 755 /path/to/website/
chmod -R 755 application/cache/
chmod -R 755 application/logs/
```

## 🔧 **TESTING DI SERVER:**

### **1. Basic Test**
- Akses: `https://yourserver.com/`
- Cek apakah homepage muncul

### **2. Database Test**  
- Akses: `https://yourserver.com/notelen/test_db_connection`
- Harus return JSON success

### **3. AJAX Test**
- Akses: `https://yourserver.com/notelen/test_ajax`  
- Test semua fungsi AJAX

## ⚠️ **TROUBLESHOOTING AJAX:**

### **Error: 500 Internal Server Error**
```
- Cek PHP error log
- Pastikan mod_rewrite aktif
- Cek file permissions
```

### **Error: AJAX Connection Failed**
```
- Cek HTTPS vs HTTP mixed content
- Pastikan firewall tidak block
- Test manual: buka URL AJAX di browser
```

### **Error: Database Connection**  
```
- Cek kredensial database
- Pastikan database exists
- Test koneksi manual via phpMyAdmin
```

## 🎯 **SERVER REQUIREMENTS:**

### **Minimum Requirements:**
- PHP 7.0+ (recommended 7.4+)
- MySQL 5.6+ / MariaDB 10.0+
- Apache 2.4+ dengan mod_rewrite
- Memory: 256MB+
- Disk: 500MB+

### **Recommended:**
- PHP 8.0+
- MySQL 8.0+
- SSL Certificate (HTTPS)
- CDN untuk assets static

## 🚨 **QUICK FIXES:**

### **1. AJAX Error 404**
```php
// Tambahkan di .htaccess
RewriteRule ^(.*)$ index.php/$1 [QSA,L]
```

### **2. Mixed Content (HTTPS/HTTP)**
```javascript
// Sudah diatasi dengan NOTELEN_CONFIG.site_url
// Auto-detect protocol
```

### **3. CSRF Token Issues**
```javascript  
// Sudah included di ajax_config.php
// Auto-handle CSRF tokens
```

## 📞 **JIKA MASIH ERROR:**

1. **Cek PHP Error Log** di server
2. **Test URL manual** di browser
3. **Cek Console Browser** untuk JS errors
4. **Contact hosting support** jika perlu

## 🔄 **POST-DEPLOYMENT:**

### **1. Security Check**
- Update default passwords
- Enable SSL/HTTPS  
- Backup database setup
- Monitor error logs

### **2. Performance**
- Enable Gzip compression
- Setup caching
- Optimize images
- CDN setup

### **3. Maintenance**
- Regular backups
- Update monitoring
- Log rotation
- Security patches

---

✅ **Sistem sudah SIAP DEPLOY dengan konfigurasi auto-detect!**

Tinggal upload file, update database config, dan test! 🚀
