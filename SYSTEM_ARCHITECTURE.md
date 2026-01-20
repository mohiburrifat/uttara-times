# Uttara Times - System Architecture & Data Flow

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      UTTARA TIMES SYSTEM                         │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         FRONTEND LAYER                           │
├─────────────────────────────────────────────────────────────────┤
│  index.php (Homepage)                                            │
│  ├── Subscribe Button → subscriptionplan.php                    │
│  ├── Advertisements Display                                      │
│  └── Pop-up Advertisements                                       │
│                                                                   │
│  User Dashboard:                                                 │
│  ├── my_subscriptions.php (View subscriptions)                  │
│  └── user_dashboard.php (Existing)                              │
│                                                                   │
│  Editor Dashboard:                                               │
│  ├── manage_plans.php (Subscription management)                 │
│  ├── manage_ads.php (Advertisement management)                  │
│  └── popup_advertisement.php (Pop-up management)                │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     APPLICATION LAYER (PHP)                      │
├─────────────────────────────────────────────────────────────────┤
│  subscription.php - Subscription purchase                       │
│  subscriptionplan.php - Display plans                           │
│  login_process.php - Login with tracking                        │
│  db.php - Database connections & helper functions              │
│  setup_subscription_system.php - Database setup                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                     DATABASE LAYER (MySQL)                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  SUBSCRIPTION MANAGEMENT:                                        │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ subscription_plans                                         │ │
│  │ ├── plan_id (PK)                                          │ │
│  │ ├── name, price, duration_days                           │ │
│  │ ├── description, features                                 │ │
│  │ └── status (active/inactive)                             │ │
│  └────────────────────────────────────────────────────────────┘ │
│                              ↓                                   │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ user_subscriptions                                         │ │
│  │ ├── subscription_id (PK)                                 │ │
│  │ ├── user_id (FK), plan_id (FK)                          │ │
│  │ ├── start_date, end_date                                │ │
│  │ ├── status, payment_status                              │ │
│  │ └── created_at                                          │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                   │
│  ADVERTISEMENT MANAGEMENT:                                       │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ advertisements                                             │ │
│  │ ├── ad_id (PK)                                           │ │
│  │ ├── title, content, image_url, link_url                │ │
│  │ ├── position (home_top/middle/bottom/sidebar)          │ │
│  │ ├── editor_id (FK)                                      │ │
│  │ ├── start_date, end_date                               │ │
│  │ └── status (active/inactive)                           │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                   │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ popup_advertisements                                       │ │
│  │ ├── popup_id (PK)                                        │ │
│  │ ├── title, content, image_url, link_url                │ │
│  │ ├── editor_id (FK)                                      │ │
│  │ ├── display_frequency                                   │ │
│  │ ├── start_date, end_date                               │ │
│  │ └── status (active/inactive)                           │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                   │
│  USER TRACKING:                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │ user_logins                                                │ │
│  │ ├── login_id (PK)                                        │ │
│  │ ├── user_id (FK)                                        │ │
│  │ ├── login_time (automatic timestamp)                   │ │
│  │ ├── ip_address, user_agent                             │ │
│  │ └── status (success/failed)                            │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                   │
│  USERS TABLE (Modified):                                        │
│  ├── Added: subscription_status (free/subscribed/expired)       │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow Diagrams

### 1. Subscription Flow

```
┌─────────────────────────────────────────────────────────────────┐
│ USER SUBSCRIPTION FLOW                                          │
└─────────────────────────────────────────────────────────────────┘

User
  │
  ├─ Clicks "Subscribe" on homepage
  │
  ↓
subscriptionplan.php (View Plans)
  │
  ├─ Displays all active subscription plans
  ├─ Shows prices, duration, features
  │
  ↓
User clicks "Subscribe Now"
  │
  ├─ Redirected to subscription.php
  │
  ↓
subscription.php (Purchase)
  │
  ├─ Validates user session
  ├─ Gets plan details from subscription_plans
  ├─ Calculates end_date = today + duration_days
  ├─ Inserts into user_subscriptions table
  ├─ Updates user.subscription_status = 'subscribed'
  │
  ↓
my_subscriptions.php (Manage)
  │
  ├─ Displays active subscription
  ├─ Shows subscription history
  ├─ Calculates days remaining
  ├─ Allows upgrade to new plan
  │
  ↓
Automatic Expiration
  │
  ├─ System checks end_date daily
  ├─ When end_date <= NOW()
  ├─ Sets status = 'expired'
  ├─ Updates user.subscription_status = 'free'
  │
  └─ User can subscribe again
```

### 2. Advertisement Display Flow

```
┌─────────────────────────────────────────────────────────────────┐
│ ADVERTISEMENT DISPLAY FLOW                                      │
└─────────────────────────────────────────────────────────────────┘

Editor Creates Ad
  │
  ├─ Goes to manage_ads.php
  ├─ Fills: title, content, image, position
  ├─ Optional: link_url, start_date, end_date
  │
  ↓
Database Insert
  │
  ├─ INSERT into advertisements table
  ├─ Sets status = 'active'
  ├─ Records editor_id, created_at
  │
  ↓
Homepage (index.php)
  │
  ├─ Queries advertisements WHERE status = 'active'
  ├─ Filters by position (home_top, home_middle, etc.)
  ├─ Checks end_date > NOW()
  │
  ↓
Display Ads
  │
  ├─ Shows in specified position
  ├─ With optional link
  ├─ Users can click to navigate
  │
  └─ Editor can disable by setting status = 'inactive'
```

### 3. Pop-up Advertisement Flow

```
┌─────────────────────────────────────────────────────────────────┐
│ POP-UP ADVERTISEMENT FLOW                                       │
└─────────────────────────────────────────────────────────────────┘

Editor Creates Pop-up
  │
  ├─ Goes to popup_advertisement.php
  ├─ Fills: title, content, image, link
  ├─ Sets display_frequency (once_per_session, always, etc.)
  │
  ↓
Database Insert
  │
  ├─ INSERT into popup_advertisements table
  ├─ Sets status = 'active'
  │
  ↓
User Visits Homepage (index.php)
  │
  ├─ PHP queries popup_advertisements WHERE status = 'active'
  ├─ Checks end_date > NOW()
  │
  ↓
Pop-up Display
  │
  ├─ JavaScript creates modal/overlay
  ├─ Shows advertisement
  ├─ User can close or click link
  │
  └─ On next page load, may show again based on frequency
```

### 4. Login Tracking Flow

```
┌─────────────────────────────────────────────────────────────────┐
│ LOGIN TRACKING FLOW                                             │
└─────────────────────────────────────────────────────────────────┘

User Visits login.php
  │
  ├─ Enters username, password, role
  │
  ↓
login_process.php
  │
  ├─ Validates credentials against users table
  │
  ├─ If Valid:
  │  ├─ Creates session
  │  ├─ Inserts into user_logins (status = 'success')
  │  ├─ Records: ip_address, user_agent, login_time
  │  └─ Redirects to dashboard
  │
  └─ If Invalid:
     ├─ Inserts into user_logins (status = 'failed')
     ├─ Records attempt details
     └─ Redirects to login with error message
```

## Helper Functions in db.php

```php
// Subscription Functions
addSubscriptionPlan($name, $price, $duration_days, $description, $features)
addUserSubscription($user_id, $plan_id)
getUserActiveSubscription($user_id)
hasActiveSubscription($user_id)
updateSubscriptionStatus($user_id)

// Advertisement Functions
addAdvertisement($title, $content, $position, $editor_id)
addPopupAdvertisement($title, $content, $editor_id)
getActiveAdvertisements($position)
getActivePopupAdvertisements()

// Login Functions
logUserLogin($user_id, $status)
```

## URL Routing

```
/index.php                          → Homepage with ads
/subscriptionplan.php               → View subscription plans
/subscription.php                   → Purchase subscription
/my_subscriptions.php               → Manage user subscriptions
/manage_plans.php                   → Editor: Manage plans
/manage_ads.php                     → Editor: Manage advertisements
/popup_advertisement.php            → Editor: Manage pop-ups
/setup_subscription_system.php      → First-time setup wizard
/login.php                          → User login (with tracking)
/login_process.php                  → Login handler (tracks attempts)
```

## Database Relationships

```
users (1) ──────→ (Many) user_subscriptions
  ↑                        ↓
  │                   subscription_plans
  │
  └─ (1) ──────→ (Many) advertisements

  └─ (1) ──────→ (Many) popup_advertisements

  └─ (1) ──────→ (Many) user_logins
```

## Access Control

```
PUBLIC (No Login Required):
├── index.php (homepage)
├── subscriptionplan.php (view plans)
├── about.php, contact.php, etc.
└── login.php

USER ONLY:
├── subscription.php (purchase)
├── my_subscriptions.php (manage)
└── user_dashboard.php

EDITOR ONLY:
├── manage_plans.php
├── manage_ads.php
└── popup_advertisement.php

ADMIN (if implemented):
├── analytics/user_logins
└── system_analysis.php
```

## File Structure

```
uttara-times/
├── index.php                           (Modified - add ads display)
├── db.php                              (Modified - add helper functions)
├── login_process.php                   (Modified - add tracking)
├── manage_plans.php                    (Modified - enhance UI)
├── manage_ads.php                      (Modified - enhance UI)
│
├── subscriptionplan.php                (New - subscription display)
├── subscription.php                    (Existing - enhanced)
├── my_subscriptions.php                (New - user dashboard)
│
├── popup_advertisement.php             (New - pop-up management)
├── setup_subscription_system.php       (New - database setup)
│
├── SUBSCRIPTION_AND_ADS_SETUP.md       (New - detailed guide)
├── IMPLEMENTATION_COMPLETE.md          (New - completion status)
└── SYSTEM_ARCHITECTURE.md              (This file)

sql/
└── subscription_and_ads_schema.sql     (New - complete schema)

uploads/
└── [advertisement images]
```

---

## Performance Considerations

1. **Database Indexing**

   - Index on user_id in user_subscriptions
   - Index on plan_id in subscription_plans
   - Index on status in advertisements

2. **Caching Strategy**

   - Cache active subscription plans
   - Cache active advertisements
   - Cache pop-up ads

3. **Query Optimization**
   - Use prepared statements (already implemented)
   - Limit advertisement queries by status and date
   - Use JOIN for subscription details

## Security Measures

1. ✓ SQL Injection Prevention (Prepared Statements)
2. ✓ Session Validation
3. ✓ Role-Based Access Control
4. ✓ Input Validation
5. ✓ XSS Prevention (htmlspecialchars where needed)

## Next Phase Recommendations

1. Payment Gateway Integration
2. Email Notifications
3. Subscription Analytics Dashboard
4. Advanced Ad Targeting
5. User Preferences Management
6. Automated Reports
7. Refund Management
8. Subscription Renewal Reminders

---

**System Architecture Complete**
**Last Updated: January 20, 2026**
