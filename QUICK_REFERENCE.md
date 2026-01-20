# Uttara Times - Quick Reference Guide

## 🚀 QUICK START (5 Minutes)

### Step 1: Initialize Database (1st time only)

```
Visit: http://localhost/uttara-times/setup_subscription_system.php
✓ Tables created
✓ Default plans added
```

### Step 2: Create Subscription Plans (Editor)

```
Login → manage_plans.php → Add Plans
Basic: 99 Rs/30 days
Premium: 199 Rs/30 days
Annual: 999 Rs/365 days
```

### Step 3: Add Advertisements (Editor)

```
Login → manage_ads.php → Add Ad
Title, Content, Position, Image
```

### Step 4: Add Pop-up Ads (Editor)

```
Login → popup_advertisement.php → Add Pop-up
Title, Content, Image, Frequency
```

### Step 5: Test System (User)

```
Homepage → Click "Subscribe"
→ View Plans → Subscribe → Check my_subscriptions.php
```

---

## 📍 KEY URLs

### For Users

- Homepage: `/index.php`
- Subscription Plans: `/subscriptionplan.php`
- My Subscriptions: `/my_subscriptions.php`
- Buy Subscription: `/subscription.php`

### For Editors

- Manage Plans: `/manage_plans.php`
- Manage Ads: `/manage_ads.php`
- Manage Pop-ups: `/popup_advertisement.php`

### For Admins/Developers

- Database Setup: `/setup_subscription_system.php`
- System Analysis: `/system_analysis.php`

---

## 🎯 WHAT USERS SEE

### Homepage

```
[NAVBAR]
├── FAQ | About Us | Our Team | 🔴 SUBSCRIBE
├── [Advertisement Banner]
├── [Pop-up Advertisement]
└── [Article Feed]
```

### When They Click "Subscribe"

```
Beautiful subscription plans with:
├── Plan Name
├── Price (Rs.)
├── Duration (Days)
├── Features List
└── [Subscribe Now] Button
```

### After Subscription

```
My Subscriptions Dashboard
├── Current Plan: Premium
├── Price: 199 Rs/month
├── Status: Active ✓
├── Days Remaining: 15
└── [Upgrade Plan] Button
```

---

## 👨‍💼 WHAT EDITORS DO

### Create Plans

```
manage_plans.php
├── Plan Name: "Gold Plan"
├── Price: 499
├── Days: 60
├── Description: "Premium with extra features"
└── Features: "Ad-free, Early access, Support"
```

### Add Advertisements

```
manage_ads.php
├── Title: "Amazing Offer"
├── Content: "Get 50% off!"
├── Position: "Home Top"
├── Image: [Upload]
├── Link: "https://..."
└── Status: Active
```

### Pop-up Ads

```
popup_advertisement.php
├── Title: "Limited Time"
├── Content: "Subscribe now!"
├── Image: [Upload]
└── Show: "Once per session"
```

---

## 💾 DATABASE QUICK REFERENCE

### View Subscription Plans

```sql
SELECT * FROM subscription_plans WHERE status = 'active';
```

### View User Subscriptions

```sql
SELECT * FROM user_subscriptions WHERE user_id = 1;
```

### View Advertisements

```sql
SELECT * FROM advertisements WHERE status = 'active';
```

### View Pop-up Ads

```sql
SELECT * FROM popup_advertisements WHERE status = 'active';
```

### View Login History

```sql
SELECT * FROM user_logins WHERE user_id = 1 ORDER BY login_time DESC;
```

### Check Expired Subscriptions

```sql
SELECT * FROM user_subscriptions WHERE end_date < NOW();
```

---

## 🔐 USER ROLES & ACCESS

### Regular User

- ✓ View homepage
- ✓ View subscription plans
- ✓ Purchase subscription
- ✓ Manage their subscriptions
- ✗ Cannot create plans or ads

### Editor

- ✓ All user features
- ✓ Create subscription plans
- ✓ Create advertisements
- ✓ Manage pop-up ads
- ✗ Cannot access other editors' data

### Admin (Optional)

- ✓ All features
- ✓ View all data
- ✓ System administration

---

## 📊 SUBSCRIPTION STATUSES

### For Plans

- `active` - Users can subscribe
- `inactive` - Hidden from users

### For User Subscriptions

- `active` - Currently subscribed
- `expired` - Subscription period ended
- `cancelled` - User cancelled

### Payment Status

- `pending` - Waiting for payment
- `completed` - Payment successful
- `failed` - Payment failed

---

## 🎨 DESIGN HIGHLIGHTS

### Subscription Plans Page

```
- Gradient purple background
- Card-based layout
- "Best Offer" badge on featured plan
- Hover animations
- Mobile responsive
- Clear pricing
- Feature list with icons
```

### Advertisements

```
- Custom positioning (Top, Middle, Bottom, Sidebar)
- Image + text support
- External links
- Start/end date scheduling
- Status management
```

### Pop-ups

```
- Modal overlay style
- Display frequency control
- Dismissable
- Beautiful styling
- Mobile friendly
```

---

## 🐛 COMMON ISSUES & FIXES

### Issue: "Tables don't exist"

```
→ Run setup_subscription_system.php
→ Check database connection in db.php
→ Verify permissions
```

### Issue: "Ads not showing"

```
→ Check status = 'active' in advertisements table
→ Verify position is set correctly
→ Check end_date > NOW()
```

### Issue: "Subscription not saving"

```
→ Verify user is logged in (session check)
→ Check user_id exists
→ Verify plan_id exists
```

### Issue: "Pop-ups not appearing"

```
→ Check status = 'active' in popup_advertisements
→ Verify index.php popup code exists
→ Check browser console for errors
```

### Issue: "Login not tracked"

```
→ Verify user_logins table exists
→ Check database write permissions
→ Check if user_id is valid
```

---

## 📈 MONITORING

### User Activity

```
- View user_logins table
- Track successful/failed attempts
- Monitor IP addresses
- Check user agents
```

### Subscription Activity

```
- Expiring subscriptions: WHERE end_date < DATE_ADD(NOW(), INTERVAL 7 DAY)
- Revenue: SELECT SUM(price) FROM subscriptions WHERE MONTH(created_at) = MONTH(NOW())
- Active users: COUNT DISTINCT user_id WHERE subscription_status = 'subscribed'
```

### Advertisement Performance

```
- Track which ads are active
- Monitor click-through rates (if logged)
- Check ad positions popularity
```

---

## 🔧 HELPER FUNCTIONS

### In db.php

#### Subscriptions

```php
addSubscriptionPlan($name, $price, $duration, $description, $features)
addUserSubscription($user_id, $plan_id)
getUserActiveSubscription($user_id)
hasActiveSubscription($user_id)
updateSubscriptionStatus($user_id)
```

#### Advertisements

```php
addAdvertisement($title, $content, $position, $editor_id)
addPopupAdvertisement($title, $content, $editor_id)
getActiveAdvertisements($position)
getActivePopupAdvertisements()
```

#### Login

```php
logUserLogin($user_id, $status)
```

---

## 📝 USAGE EXAMPLES

### Add a Plan Programmatically

```php
require 'db.php';
addSubscriptionPlan(
    "Pro Plan",
    299.00,
    30,
    "Professional subscription",
    "Ad-free, Priority support, Exclusive content"
);
```

### Check User Subscription

```php
require 'db.php';
if (hasActiveSubscription(5)) {
    echo "User has active subscription";
}
```

### Get User's Subscription

```php
require 'db.php';
$sub = getUserActiveSubscription(5);
if ($sub) {
    echo "Plan: " . $sub['name'];
    echo "Days Left: " . daysLeft($sub['end_date']);
}
```

### Show Ads for Position

```php
require 'db.php';
$ads = getActiveAdvertisements('home_top');
while ($ad = $ads->fetch_assoc()) {
    echo '<div class="ad">' . $ad['content'] . '</div>';
}
```

---

## 📚 DOCUMENTATION FILES

1. **SUBSCRIPTION_AND_ADS_SETUP.md** - Detailed setup guide
2. **SYSTEM_ARCHITECTURE.md** - System design & data flow
3. **IMPLEMENTATION_COMPLETE.md** - What's been implemented
4. **QUICK_REFERENCE.md** - This file (quick lookup)

---

## 💡 TIPS & TRICKS

### Disable All Ads Quickly

```sql
UPDATE advertisements SET status = 'inactive';
UPDATE popup_advertisements SET status = 'inactive';
```

### Force Subscription Expiry Check

```php
require 'db.php';
foreach ($active_users as $user) {
    updateSubscriptionStatus($user['user_id']);
}
```

### Export Login Report

```sql
SELECT DATE(login_time), COUNT(*) as count, status
FROM user_logins
GROUP BY DATE(login_time), status;
```

### Find Most Popular Plan

```sql
SELECT plan_id, COUNT(*) as subscribers
FROM user_subscriptions
WHERE status = 'active'
GROUP BY plan_id
ORDER BY subscribers DESC;
```

---

## 🎓 LEARNING PATH

1. Read: IMPLEMENTATION_COMPLETE.md (5 min)
2. Run: setup_subscription_system.php (1 min)
3. Create: Sample subscription plans (3 min)
4. Create: Sample advertisement (3 min)
5. Test: Subscribe as user (5 min)
6. Read: SYSTEM_ARCHITECTURE.md (10 min)
7. Explore: Database tables (5 min)

**Total: ~30 minutes to understand the system**

---

## 🚨 IMPORTANT REMINDERS

- ✓ Always run setup file first
- ✓ Create at least 1 plan before testing
- ✓ Check database connection if errors occur
- ✓ Use prepared statements for all queries
- ✓ Validate user sessions before allowing subscriptions
- ✓ Regular database backups recommended
- ✓ Monitor user_logins for security issues

---

**Quick Reference Guide Complete**
**Ready to Use!**
**Last Updated: January 20, 2026**
