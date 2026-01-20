# Uttara Times - Subscription & Advertisement System Setup Guide

## Overview

This document explains the new features added to Uttara Times, including subscription plans, advertisements, pop-up ads, and user login tracking.

## New Features Added

### 1. **Subscription Plans System**

- **Database Tables:**

  - `subscription_plans` - Stores all available subscription plans
  - `user_subscriptions` - Tracks user subscriptions

- **Features:**

  - Editors can create and manage subscription plans
  - Users can view available plans and subscribe
  - Users can track their active subscriptions
  - Automatic subscription expiration handling

- **Files:**
  - `manage_plans.php` - Editor panel to create/edit subscription plans
  - `subscription.php` - Display all plans to users (available from home page)
  - `subscriptionplan.php` - Beautiful subscription plans page (linked from home page)
  - `my_subscriptions.php` - User dashboard for managing subscriptions

### 2. **Advertisement Management**

- **Database Tables:**

  - `advertisements` - Stores all advertisements
  - `popup_advertisements` - Stores pop-up ads

- **Features:**

  - Editors can add text and image-based advertisements
  - Ads can be positioned in different locations (homepage top, middle, bottom, sidebar)
  - Ads are displayed on the homepage automatically
  - Pop-up ads can be shown with different display frequencies

- **Files:**
  - `manage_ads.php` - Editor panel to create/edit advertisements
  - `popup_advertisement.php` - Editor panel to manage pop-up advertisements
  - Ads display automatically on index.php homepage

### 3. **User Login Tracking**

- **Database Table:**

  - `user_logins` - Tracks all user login attempts (success/failed)

- **Features:**

  - Records login time, IP address, and user agent
  - Tracks successful and failed login attempts
  - Helps with security monitoring

- **Automatic:** Integrated into login_process.php

### 4. **User Subscription Status**

- **Database Column:**
  - Added `subscription_status` column to `users` table (free/subscribed/expired)

## Installation Instructions

### Step 1: Run Database Setup

1. Open your browser and go to: `http://localhost/uttara-times/setup_subscription_system.php`
2. This will automatically create all required tables and insert default subscription plans
3. You should see success messages for all tables created

### Step 2: Add Subscription Plans (Editor Only)

1. Login as an editor
2. Go to: `http://localhost/uttara-times/manage_plans.php`
3. Fill in the plan details:
   - Plan Name (e.g., "Basic Plan")
   - Price (e.g., 99.00)
   - Duration in Days (e.g., 30)
   - Description (e.g., "Basic subscription with access to all articles")
4. Click "Add Plan"

### Step 3: Display Subscription Link on Homepage

The subscription link is already added to the homepage navigation:

- Navigate to navigation section labeled "Subscribe" (appears in red color)
- Clicking will take users to the subscription plans page

### Step 4: Add Advertisements (Editor Only)

1. Login as an editor
2. Go to: `http://localhost/uttara-times/manage_ads.php`
3. Fill in advertisement details:
   - Title
   - Content
   - Position (Homepage Top, Middle, Bottom, or Sidebar)
   - Link URL (optional)
   - Upload image (optional)
4. Click "Add Advertisement"
5. Advertisements will appear on the homepage automatically

### Step 5: Add Pop-up Advertisements (Editor Only)

1. Login as an editor
2. Go to: `http://localhost/uttara-times/popup_advertisement.php`
3. Fill in pop-up details:
   - Title
   - Content
   - Link URL (optional)
   - Upload image (optional)
4. Click "Add Advertisement"
5. Pop-ups will display on the homepage automatically

## User Features

### For Regular Users:

1. **View Subscription Plans:**

   - Click "Subscribe" button in homepage navigation
   - View all available plans with prices and features
   - Click "Subscribe Now" to subscribe

2. **Manage Subscriptions:**

   - Go to: `http://localhost/uttara-times/my_subscriptions.php`
   - View active subscription details
   - See subscription history
   - View days remaining on current subscription
   - Upgrade to a different plan

3. **View Advertisements:**
   - Advertisements appear automatically on the homepage
   - Pop-up ads may appear during browsing

### For Editors:

1. **Manage Subscription Plans** (`manage_plans.php`)

   - Create new plans
   - Edit existing plans
   - Delete plans
   - Activate/deactivate plans

2. **Manage Advertisements** (`manage_ads.php`)

   - Upload advertisements with images or text
   - Set ad position on site
   - Track ad status
   - Add external links to ads

3. **Manage Pop-up Ads** (`popup_advertisement.php`)
   - Create pop-up advertisements
   - Set display frequency
   - Track active/inactive pop-ups

## Database Schema

### subscription_plans

```sql
- plan_id (Auto-increment Primary Key)
- name (varchar)
- price (decimal)
- duration_days (int)
- description (text)
- features (text)
- created_at (timestamp)
- status (enum: active/inactive)
```

### user_subscriptions

```sql
- subscription_id (Auto-increment Primary Key)
- user_id (Foreign Key)
- plan_id (Foreign Key)
- start_date (timestamp)
- end_date (datetime)
- status (enum: active/expired/cancelled)
- payment_status (enum: pending/completed/failed)
- created_at (timestamp)
```

### advertisements

```sql
- ad_id (Auto-increment Primary Key)
- title (varchar)
- content (text)
- image_url (varchar)
- link_url (varchar)
- position (varchar)
- editor_id (Foreign Key)
- start_date (timestamp)
- end_date (datetime)
- status (enum: active/inactive)
- created_at (timestamp)
```

### popup_advertisements

```sql
- popup_id (Auto-increment Primary Key)
- title (varchar)
- content (text)
- image_url (varchar)
- link_url (varchar)
- editor_id (Foreign Key)
- display_frequency (varchar)
- start_date (timestamp)
- end_date (datetime)
- status (enum: active/inactive)
- created_at (timestamp)
```

### user_logins

```sql
- login_id (Auto-increment Primary Key)
- user_id (Foreign Key)
- login_time (timestamp)
- ip_address (varchar)
- user_agent (text)
- status (enum: success/failed)
```

## Navigation Integration

### Homepage (index.php)

- "Subscribe" link in top navigation bar takes users to subscription plans
- Advertisements display automatically below the navbar
- Pop-up advertisements display at the beginning of the page body

## Next Steps

1. Run the setup file: `setup_subscription_system.php`
2. Login as an editor
3. Create subscription plans in `manage_plans.php`
4. Add advertisements in `manage_ads.php`
5. Test the subscription flow as a user
6. Monitor login activity in `user_logins` table

## Troubleshooting

### Tables not created:

- Check database connection in `db.php`
- Ensure user has permission to create tables
- Check error messages on setup page

### Subscription not saving:

- Verify `user_subscriptions` table exists
- Check that `users` table has the required columns
- Review database error logs

### Ads not appearing:

- Verify advertisements table has `status = 'active'`
- Check that position is correctly set
- Ensure index.php is displaying ads section

## Support

For issues or questions, check the database setup file or contact the development team.
