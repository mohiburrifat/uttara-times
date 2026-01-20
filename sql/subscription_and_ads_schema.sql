-- Updated Database Schema for Uttara Times with Subscriptions and Advertisements

-- Table for Subscription Plans
CREATE TABLE IF NOT EXISTS `subscription_plans` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `description` text,
  `features` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for User Subscriptions
CREATE TABLE IF NOT EXISTS `user_subscriptions` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`subscription_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for Advertisements
CREATE TABLE IF NOT EXISTS `advertisements` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255),
  `link_url` varchar(255),
  `position` varchar(50) DEFAULT 'home_top',
  `editor_id` int(11),
  `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ad_id`),
  FOREIGN KEY (`editor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for Pop-up Advertisements
CREATE TABLE IF NOT EXISTS `popup_advertisements` (
  `popup_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255),
  `link_url` varchar(255),
  `editor_id` int(11),
  `display_frequency` varchar(50) DEFAULT 'once_per_session',
  `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`popup_id`),
  FOREIGN KEY (`editor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for User Logins (Track user login activity)
CREATE TABLE IF NOT EXISTS `user_logins` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45),
  `user_agent` text,
  `status` enum('success','failed') DEFAULT 'success',
  PRIMARY KEY (`login_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add subscription_status column to users table if it doesn't exist
ALTER TABLE `users` ADD COLUMN `subscription_status` enum('free','subscribed','expired') DEFAULT 'free' AFTER `role`;

-- Insert default subscription plans
INSERT INTO `subscription_plans` (`name`, `price`, `duration_days`, `description`, `features`, `status`) VALUES
('Basic Plan', 99.00, 30, 'Basic subscription with access to all articles', 'Access to articles, Ad-free reading', 'active'),
('Premium Plan', 199.00, 30, 'Premium subscription with exclusive content', 'Access to exclusive articles, Ad-free reading, Early access to new articles', 'active'),
('Annual Plan', 999.00, 365, 'Annual subscription with best value', 'All Premium features, Yearly discount, Priority support', 'active');
