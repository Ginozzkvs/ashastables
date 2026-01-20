# Local Testing Guide - XAMPP Setup

## Step 1: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start these services:
   - **Apache** (Click "Start")
   - **MySQL** (Click "Start")
3. Verify they show "Running" in green

---

## Step 2: Create Database

1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" on left sidebar
3. Enter database name: `farm_system`
4. Click "Create"

---

## Step 3: Configure .env File

1. Open project folder: `c:\Users\acerzz\farm-system`
2. Find `.env.example` file and copy it
3. Rename copy to `.env`
4. Edit `.env` file and update:

```env
APP_NAME=ASHA
APP_ENV=local
APP_DEBUG=true
APP_KEY=             # Leave empty, we'll generate it

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farm_system
DB_USERNAME=root
DB_PASSWORD=          # Leave empty (XAMPP default)
```

---

## Step 4: Install Dependencies & Generate App Key

Open **PowerShell** in project folder (right-click → Open PowerShell here):

```powershell
# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

## Step 5: Run Migrations

In PowerShell (same folder):

```powershell
# Run migrations to create tables
php artisan migrate

# Seed with test data (optional)
php artisan db:seed
```

---

## Step 6: Create Test Admin User

In PowerShell:

```powershell
# Open Tinker interactive shell
php artisan tinker

# Create admin user
>>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('admin123'), 'role' => 'admin'])
>>> exit
```

---

## Step 7: Start Laravel Development Server

In PowerShell:

```powershell
# Start development server
php artisan serve
```

Server runs at: `http://localhost:8000`

---

## Step 8: Create Test Data

### Login First
- URL: `http://localhost:8000/login`
- Email: `admin@test.com`
- Password: `admin123`

### Create Membership (if not exists)
1. Go to: `http://localhost:8000/memberships`
2. Click "+ Add Membership"
3. Fill in:
   - **Name**: Premium
   - **Price**: 99.99
   - **Duration**: 365
4. Click "Create Membership"

### Create Activity (if not exists)
1. Go to: `http://localhost:8000/activities`
2. Click "+ Add Activity"
3. Fill in:
   - **Name**: Horse Riding
   - **Unit**: minutes
4. Click "Create Activity"

### Create Activity Limit
1. Go to: `http://localhost:8000/membership-activity-limits`
2. Click "+ Add Limit"
3. Fill in:
   - **Membership**: Premium
   - **Activity**: Horse Riding
   - **Max Per Year**: 12
4. Click "Create"

### Create Test Member
1. Go to: `http://localhost:8000/members`
2. Click "+ Add Member"
3. Fill in:
   - **Name**: John Doe
   - **Email**: john@test.com
   - **Card UID**: TEST123456
   - **Membership**: Premium
4. Click "Create Member"

---

## Step 9: Test the Scanner Page

1. Go to: `http://localhost:8000/staff/scan`
2. You should see:
   - ASHA header with logo
   - NFC scan panel (gold border)
   - "READY TO AUTHENTICATE" message

---

## Step 10: Test Card Scanning

### Simulate Card Scan (Manual Input)
1. Click on the scan panel
2. Type the test card UID: `TEST123456`
3. Press **Enter**

### Expected Result:
- Member info card appears showing:
  - Name: John Doe
  - Valid Until: (membership expiry date)
  - Status: ACTIVE
- Activity card appears showing:
  - Activity: Horse Riding
  - Used: 0
  - Remaining: 12
  - Total: 12
  - Progress bar
  - "Reserve Session" button

---

## Step 11: Test Activity Usage & Receipt

1. Click "Reserve Session" button
2. Modal appears: "CONFIRM SESSION"
3. Click "Confirm"

### Expected Result:
- Success modal: "SESSION RESERVED"
- Receipt window opens automatically
- **58mm Receipt prints with:**
  - ASHA header
  - Receipt #, Date, Time
  - Member details
  - Activity details
  - Session status (1 Used, 11 Remaining)
  - Thank you message
- Receipt window closes
- Scanner resets for next card

---

## Step 12: Verify Database Changes

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Select `farm_system` database
3. Check these tables:
   - `members` - should have John Doe
   - `activities` - should have Horse Riding
   - `activity_logs` - should have new entry
   - `member_activity_balances` - should show 1 used, 11 remaining

---

## Common Issues & Fixes

### Issue: Port 8000 already in use
```powershell
php artisan serve --port=8001
# Then use: http://localhost:8001
```

### Issue: MySQL won't start in XAMPP
- Make sure no other MySQL is running
- Try: Control Panel → Admin Tools → Services → Stop MySQL services

### Issue: "APP_KEY not set"
```powershell
php artisan key:generate
```

### Issue: "Class 'PDO' not found"
- Enable PHP extensions in XAMPP:
  - Go to XAMPP Control Panel
  - Click "Config" on Apache row
  - Click "PHP (php.ini)"
  - Uncomment: `;extension=pdo_mysql` → `extension=pdo_mysql`
  - Restart Apache

### Issue: Database connection refused
- Verify MySQL is running in XAMPP
- Check DB_HOST in .env is `127.0.0.1` (not localhost)
- Verify DB_USERNAME is `root` and DB_PASSWORD is empty

---

## Testing Checklist

- [ ] XAMPP Apache running
- [ ] XAMPP MySQL running
- [ ] Database `farm_system` created
- [ ] .env configured
- [ ] `php artisan migrate` successful
- [ ] Admin user created
- [ ] Test membership created
- [ ] Test activity created
- [ ] Test activity limit created
- [ ] Test member created with card UID
- [ ] Scanner page loads at `/staff/scan`
- [ ] Member info displays when card scanned
- [ ] Activity card shows remaining sessions
- [ ] Receipt prints when activity used
- [ ] Scanner resets after activity used
- [ ] Database shows activity_logs entry

---

## Quick Start Command Summary

```powershell
# 1. Install
composer install

# 2. Setup
php artisan key:generate

# 3. Database
php artisan migrate

# 4. Create user (use tinker)
php artisan tinker
# >>> User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('admin123'), 'role' => 'admin'])
# >>> exit

# 5. Serve
php artisan serve

# 6. Open in browser
# http://localhost:8000
```

---

## Test Endpoints (for API testing)

All require admin authentication.

### Find Member by Card UID
```
POST http://localhost:8000/staff/activity/member
Content-Type: application/json

{
  "card_uid": "TEST123456"
}
```

### Use Activity
```
POST http://localhost:8000/staff/activity/use
Content-Type: application/json

{
  "card_uid": "TEST123456",
  "activity_id": 1
}
```

### View Receipt
```
GET http://localhost:8000/staff/receipt/1
```
(Replace 1 with actual activity_log ID)

---

## Browser Console (For Debugging)

Open browser DevTools: **F12**

### View API Responses
1. Go to "Network" tab
2. Click "staff/activity/member"
3. View "Response" tab to see JSON

### View Scanner State
In Console tab:
```javascript
// Check Alpine.js component data
// (if scan page open)
console.log(document.body.__alpineInstance)
```

---

**Need Help?** Check error messages in:
- Laravel: `storage/logs/laravel.log`
- XAMPP: `xampp/apache/logs/error.log`
