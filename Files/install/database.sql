-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 12:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vinance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `email_verified_at`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admins', 'admin@site.com', 'admin', NULL, '6721d94c3aa821730271564.png', '$2y$10$ttArcUVDiEZf1cz7K4DJO.k01pcV.wOqbta/MWzkKW98HWS24c6Dq', 'VALMSjAmB2RvwEaOtIvyZWw1gAQwhxYRYSBT48jPWeNHSIFFiO2ZqkJ6lJg9', NULL, '2024-10-30 06:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `click_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `binary_trades`
--

CREATE TABLE `binary_trades` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `coin_pair_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `last_price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `result_price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `win_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `profit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `duration` int(11) NOT NULL DEFAULT 0,
  `trade_ended_at` datetime DEFAULT NULL,
  `direction` varchar(40) DEFAULT NULL,
  `result` varchar(40) DEFAULT NULL,
  `trx` varchar(40) DEFAULT NULL,
  `win_status` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coin_pairs`
--

CREATE TABLE `coin_pairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `listed_market_name` text DEFAULT NULL,
  `symbol` text DEFAULT NULL,
  `market_id` int(11) NOT NULL DEFAULT 0,
  `coin_id` int(11) NOT NULL DEFAULT 0,
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `minimum_buy_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `maximum_buy_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `minimum_sell_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `maximum_sell_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `percent_charge_for_sell` decimal(5,2) NOT NULL DEFAULT 0.00,
  `percent_charge_for_buy` decimal(5,2) NOT NULL DEFAULT 0.00,
  `binary_trade_profit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `min_binary_trade_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `max_binary_trade_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `binary_trade_duration` text DEFAULT NULL,
  `binary_increment_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `last_liquidation_check_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=enable,0=disable',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=yes,0=no',
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `alias` varchar(40) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cron_schedule_id` int(11) NOT NULL DEFAULT 0,
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_jobs`
--

INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Update Crypto', 'update_crypto', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"crypto\"]', NULL, 3, '2024-10-30 06:45:02', '2024-10-30 06:40:02', 1, 1, '2023-06-21 11:29:14', '2024-10-30 00:40:02'),
(2, 'Update Coin Pair', 'coin_pair', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"market\"]', NULL, 3, '2024-10-30 06:45:02', '2024-10-30 06:40:02', 1, 1, '2023-06-21 11:29:14', '2024-10-30 00:40:02'),
(3, 'Trade', 'trade', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"trade\"]', NULL, 2, '2024-10-30 06:41:12', '2024-10-30 06:40:12', 1, 1, '2023-06-21 11:29:14', '2024-10-30 00:40:12'),
(4, 'Stop Limit Order', 'stop_limit_order', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"stopLimitOrder\"]', NULL, 2, '2024-10-30 06:41:12', '2024-10-30 06:40:12', 1, 1, NULL, '2024-10-30 00:40:12'),
(5, 'Incomplete Binary Trade', 'incompleteBinary', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"incompleteBinary\"]', NULL, 2, '2024-10-30 07:29:03', '2024-10-30 07:28:03', 1, 1, '2023-06-21 05:29:14', '2024-10-30 01:28:03'),
(6, 'Future Queue Order', 'future_queue_order', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"futureQueueOrder\"]', NULL, 2, '2025-02-23 12:35:03', '2025-02-23 12:34:03', 1, 1, '2023-06-21 05:29:14', '2025-02-23 06:34:03'),
(7, 'Future Liquidation Order', 'future_liquidation_order', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"futureLiquidationOrder\"]', NULL, 2, '2025-02-23 12:35:03', '2025-02-23 12:34:03', 1, 1, '2023-06-21 05:29:14', '2025-02-23 06:34:03');

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_logs`
--

CREATE TABLE `cron_job_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cron_job_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedules`
--

CREATE TABLE `cron_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `interval` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_schedules`
--

INSERT INTO `cron_schedules` (`id`, `name`, `interval`, `status`, `created_at`, `updated_at`) VALUES
(1, '3 Minutes', 180, 1, '2023-07-30 00:54:30', '2023-08-28 19:16:19'),
(2, '1 Minute', 60, 1, '2023-08-28 19:15:46', '2023-08-28 19:15:46'),
(3, '5', 300, 1, '2023-09-05 09:58:19', '2023-09-05 09:58:19');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Crypto,2=Fiat',
  `name` varchar(255) DEFAULT NULL,
  `sign` varchar(255) DEFAULT NULL,
  `symbol` varchar(55) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'only for fiat currency',
  `ranking` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Enable,0=Disable',
  `highlighted_coin` tinyint(1) NOT NULL DEFAULT 0,
  `p2p_sn` int(11) NOT NULL DEFAULT 0,
  `last_update` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency_data_providers`
--

CREATE TABLE `currency_data_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` text DEFAULT NULL,
  `configuration` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=crypto,2=Fiat',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=enable,0=disable',
  `help` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `instruction` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_data_providers`
--

INSERT INTO `currency_data_providers` (`id`, `name`, `alias`, `configuration`, `type`, `status`, `help`, `image`, `is_default`, `instruction`, `created_at`, `updated_at`) VALUES
(1, 'Coinmarketcap', 'CoinmarketCap', '{\"api_key\":{\"title\":\"API Key\",\"value\":\"-------------------\"}}', 1, 1, NULL, 'coinmarketcap.jpg', 1, '<ul class=\"list-group list-group-flush\">\n <li class=\"list-group-item\">Go to the CoinMarketCap website <a href=\"https://coinmarketcap.com/api\" target=\"_blank\">https://coinmarketcap.com/api</a></li>\n <li class=\"list-group-item\">Signup this platform or login existing account</li>\n <li class=\"list-group-item\">After logging into your CoinMarketCap account, Choose an API Plan</li>\n <li class=\"list-group-item\">Generate an API Key</li>\n <li class=\"list-group-item\">Copy API key & configure here</li>\n </ul>', NULL, '2024-10-30 01:23:28'),
(2, 'Cryptocompare', 'Cryptocompare', '{\"api_key\":{\"title\":\"API Key\",\"value\":\"------------------------\"}}', 1, 1, NULL, 'cryptocompare.jpg', 0, ' <ul class=\"list-group list-group-flush\">\n                        <li class=\"list-group-item\">Go to the Cryptocompare website <a href=\"https://min-api.cryptocompare.com/\" target=\"_blank\">https://min-api.cryptocompare.com/</a></li>\n                        <li class=\"list-group-item\">Signup this platform or login to the existing account</li>\n                        <li class=\"list-group-item\">After logging into your Cryptocompare account, Choose an API Plan</li>\n                        <li class=\"list-group-item\">Generate an API Key</li>\n                        <li class=\"list-group-item\">Copy API key & configure here</li>\n                    </ul>', NULL, '2024-10-30 01:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `wallet_id` int(11) NOT NULL DEFAULT 0,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `method_code` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `method_currency` varchar(40) DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `final_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `detail` text DEFAULT NULL,
  `btc_amount` varchar(255) DEFAULT NULL,
  `btc_wallet` varchar(255) DEFAULT NULL,
  `trx` varchar(40) DEFAULT NULL,
  `payment_try` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT 0,
  `is_web` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'This will be 1 if the request is from NextJs application',
  `admin_feedback` text DEFAULT NULL,
  `success_url` varchar(255) DEFAULT NULL,
  `failed_url` varchar(255) DEFAULT NULL,
  `last_cron` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_app` tinyint(1) NOT NULL DEFAULT 0,
  `token` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `act` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `script` text DEFAULT NULL,
  `shortcode` text DEFAULT NULL COMMENT 'object',
  `support` text DEFAULT NULL COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tawk-chat', 'Tawk.to', 'Key location is shown bellow', 'tawky_big.png', '<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"------\"}}', 'twak.png', 0, '2019-10-18 23:16:05', '2022-03-22 05:22:24'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB\"}}', 'recaptcha.png', 0, '2019-10-18 23:16:05', '2024-10-30 01:27:36'),
(3, 'custom-captcha', 'Custom Captcha', 'Just put any random string', 'customcaptcha.png', NULL, '{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}', 'na', 0, '2019-10-18 23:16:05', '2023-08-24 14:53:03'),
(4, 'google-analytics', 'Google Analytics', 'Key location is shown bellow', 'google_analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>', '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}', 'ganalytics.png', 0, NULL, '2021-05-04 10:19:12'),
(5, 'fb-comment', 'Facebook Comment ', 'Key location is shown bellow', 'Facebook.png', '<div id=\"fb-root\"></div><script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1\"></script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----\"}}', 'fb_com.PNG', 0, NULL, '2022-03-22 05:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_pairs`
--

CREATE TABLE `favorite_pairs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `pair_id` int(10) UNSIGNED NOT NULL,
  `future_trade_config_id` int(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `act` varchar(40) DEFAULT NULL,
  `form_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data_keys` varchar(40) DEFAULT NULL,
  `data_values` longtext DEFAULT NULL,
  `seo_content` longtext DEFAULT NULL,
  `tempname` varchar(40) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"tradeing\",\"crypto\",\"crypto buy\",\"crypto sell\",\"crypto currency\",\"vinance\"],\"description\":\"VINANCE- Digital Trading Platform. That will take your excitement to the next level! Get ready to experience the ultimate thrill of winning as we bring you a cutting-edge trading experience with Vinance.\",\"social_title\":\"Vinance - Digital Trading Platform\",\"social_description\":\"VINANCE- Digital Trading Platform. That will take your excitement to the next level! Get ready to experience the ultimate thrill of winning as we bring you a cutting-edge trading experience with Vinance.\",\"image\":\"6721e1ca2b4c61730273738.png\"}', NULL, 'basic', '', '2020-07-04 23:42:52', '2024-10-30 01:35:39'),
(24, 'about.content', '{\"has_image\":\"1\",\"heading\":\"Latest News\",\"sub_heading\":\"Register New Account\",\"description\":\"fdg sdfgsdf g ggg\",\"about_icon\":\"<i class=\\\"las la-address-card\\\"><\\/i>\",\"background_image\":\"60951a84abd141620384388.png\",\"about_image\":\"5f9914e907ace1603867881.jpg\"}', NULL, 'basic', NULL, '2020-10-28 00:51:20', '2021-05-07 10:16:28'),
(25, 'blog.content', '{\"heading\":\"Vinance Latest News\",\"subheading\":\"------\"}', NULL, 'basic', NULL, '2020-10-28 00:51:34', '2023-07-10 07:23:20'),
(26, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Updates on Minimum Order Size for Spot and Margin Trading Pairs\",\"description_nic\":\"<div>\\n\\n<h3><font size=\\\"4\\\">The standard Lorem Ipsum passage, used since the 1500s<\\/font><\\/h3><p>\\\"Lorem\\n ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \\nmollit anim id est laborum.\\\"<\\/p><p><br \\/><\\/p><h3><font size=\\\"4\\\">Section 1.10.32 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3><p>\\\"Sed\\n ut perspiciatis unde omnis iste natus error sit voluptatem accusantium \\ndoloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo \\ninventore veritatis et quasi architecto beatae vitae dicta sunt \\nexplicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut \\nodit aut fugit, sed quia consequuntur magni dolores eos qui ratione \\nvoluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum \\nquia dolor sit amet, consectetur, adipisci velit, sed quia non numquam \\neius modi tempora incidunt ut labore et dolore magnam aliquam quaerat \\nvoluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam \\ncorporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?\\n Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse \\nquam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo \\nvoluptas nulla pariatur?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"But I must explain to you how all this mistaken idea of denouncing \\npleasure and praising pain was born and I will give you a complete \\naccount of the system, and expound the actual teachings of the great \\nexplorer of the truth, the master-builder of human happiness. No one \\nrejects, dislikes, or avoids pleasure itself, because it is pleasure, \\nbut because those who do not know how to pursue pleasure rationally \\nencounter consequences that are extremely painful. Nor again is there \\nanyone who loves or pursues or desires to obtain pain of itself, because\\n it is pain, but because occasionally circumstances occur in which toil \\nand pain can procure him some great pleasure. To take a trivial example,\\n which of us ever undertakes laborious physical exercise, except to \\nobtain some advantage from it? But who has any right to find fault with a\\n man who chooses to enjoy a pleasure that has no annoying consequences, \\nor one who avoids a pain that produces no resultant pleasure?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">Section 1.10.33 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3>\\n<p>\\\"At vero eos et accusamus et iusto odio dignissimos ducimus qui \\nblanditiis praesentium voluptatum deleniti atque corrupti quos dolores \\net quas molestias excepturi sint occaecati cupiditate non provident, \\nsimilique sunt in culpa qui officia deserunt mollitia animi, id est \\nlaborum et dolorum fuga. Et harum quidem rerum facilis est et expedita \\ndistinctio. Nam libero tempore, cum soluta nobis est eligendi optio \\ncumque nihil impedit quo minus id quod maxime placeat facere possimus, \\nomnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem \\nquibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet\\n ut et voluptates repudiandae sint et molestiae non recusandae. Itaque \\nearum rerum hic tenetur a sapiente delectus, ut aut reiciendis \\nvoluptatibus maiores alias consequatur aut perferendis doloribus \\nasperiores repellat.\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"On the other hand, we denounce with righteous indignation and \\ndislike men who are so beguiled and demoralized by the charms of \\npleasure of the moment, so blinded by desire, that they cannot foresee \\nthe pain and trouble that are bound to ensue; and equal blame belongs to\\n those who fail in their duty through weakness of will, which is the \\nsame as saying through shrinking from toil and pain. These cases are \\nperfectly simple and easy to distinguish. In a free hour, when our power\\n of choice is untrammelled and when nothing prevents our being able to \\ndo what we like best, every pleasure is to be welcomed and every pain \\navoided. But in certain circumstances and owing to the claims of duty or\\n the obligations of business it will frequently occur that pleasures \\nhave to be repudiated and annoyances accepted. The wise man therefore \\nalways holds in these matters to this principle of selection: he rejects\\n pleasures to secure other greater pleasures, or else he endures pains \\nto avoid worse pains.\\\"<\\/p>\\n<\\/div>\",\"image\":\"64eaedf0ca2941693117936.png\"}', NULL, 'basic', 'updates-on-minimum-order-size-for-spot-and-margin-trading-pairs', '2020-10-28 00:57:19', '2023-08-29 14:42:12'),
(27, 'contact_us.content', '{\"heading\":\"We Would Love To Hear From You.\",\"subheading\":\"In case you have questions regarding your Vinance account or need support, please contact us. We\'re here to assist you 7 days of week.\",\"email\":\"support@Vinance.com\",\"mobile\":\"(505) 555-0125\",\"has_image\":\"1\",\"image_light\":\"648e9c83051c21687067779.png\",\"image_dark\":\"648e9c83146601687067779.png\"}', NULL, 'basic', NULL, '2020-10-28 00:59:19', '2023-06-18 04:26:19'),
(28, 'counter.content', '{\"heading\":\"Latest News\",\"sub_heading\":\"Register New Account\"}', NULL, 'basic', NULL, '2020-10-28 01:04:02', '2020-10-28 01:04:02'),
(31, 'social_icon.element', '{\"title\":\"Facebook\",\"icon\":\"<i class=\\\"fab fa-facebook-f\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}', NULL, 'basic', NULL, '2020-11-12 04:07:30', '2023-07-15 04:05:30'),
(33, 'feature.content', '{\"heading\":\"asdf\",\"sub_heading\":\"asdf\"}', NULL, 'basic', NULL, '2021-01-03 23:40:54', '2021-01-03 23:40:55'),
(34, 'feature.element', '{\"title\":\"asdf\",\"description\":\"asdf\",\"feature_icon\":\"asdf\"}', NULL, 'basic', NULL, '2021-01-03 23:41:02', '2021-01-03 23:41:02'),
(35, 'service.element', '{\"trx_type\":\"withdraw\",\"service_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"title\":\"asdfasdf\",\"description\":\"asdfasdfasdfasdf\"}', NULL, 'basic', NULL, '2021-03-06 01:12:10', '2021-03-06 01:12:10'),
(36, 'service.content', '{\"trx_type\":\"deposit\",\"heading\":\"asdf fffff\",\"subheading\":\"555\"}', NULL, 'basic', NULL, '2021-03-06 01:27:34', '2022-03-30 08:07:06'),
(39, 'banner.content', '{\"has_image\":\"1\",\"heading\":\"Trade Your Crypto on {{Vinance}} with Confidence\",\"subheading\":\"Signup & explore your next trading partner\",\"button_text\":\"Sign Up\",\"button_link\":\"user\\/register\",\"image_one\":\"648d9ecec9f4d1687002830.png\",\"image_two\":\"648d9eced917b1687002830.png\",\"image_three\":\"648d9ecedc3c91687002830.png\"}', NULL, 'basic', NULL, '2021-05-02 06:09:30', '2023-09-05 11:39:40'),
(41, 'cookie.data', '{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<div><h5>What information do we collect?<\\/h5><p>We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><p><br><\\/p><\\/div><div><h5>How do we protect your information?<\\/h5><p>All provided delicate\\/credit data is sent through Stripe.<br>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><p><br><\\/p><\\/div><div><h5>Do we disclose any information to outside parties?<\\/h5><p>We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><p><br><\\/p><\\/div><div><h5>Children\'s Online Privacy Protection Act Compliance<\\/h5><p>We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><p><br><\\/p><\\/div><div><h5>Changes to our Privacy Policy<\\/h5><p>If we decide to change our privacy policy, we will post those changes on this page.<\\/p><p><br><\\/p><\\/div><div><h5>How long we retain your information?<\\/h5><p>At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><p><br><\\/p><\\/div><div><h5>What we don\\u2019t do with your data<\\/h5><p>We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\",\"status\":1}', NULL, 'basic', NULL, '2020-07-04 23:42:52', '2024-03-13 23:19:54'),
(42, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<div><h5>What information do we collect?<\\/h5><p>We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><p><br \\/><\\/p><\\/div><div><h5>How do we protect your information?<\\/h5><p>All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Do we disclose any information to outside parties?<\\/h5><p>We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Children\'s Online Privacy Protection Act Compliance<\\/h5><p>We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Changes to our Privacy Policy<\\/h5><p>If we decide to change our privacy policy, we will post those changes on this page.<\\/p><p><br \\/><\\/p><\\/div><div><h5>How long we retain your information?<\\/h5><p>At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><p><br \\/><\\/p><\\/div><div><h5>What we don\\u2019t do with your data<\\/h5><p>We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', NULL, 'basic', 'privacy-policy', '2021-06-09 08:50:42', '2024-03-13 23:06:19'),
(43, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<br \\/><div><h5>Terms & Conditions for Users<\\/h5><p>Before getting to this site, you are consenting to be limited by these site Terms and Conditions of Use, every single appropriate law, and guidelines, and concur that you are answerable for consistency with any material neighborhood laws. If you disagree with any of these terms, you are restricted from utilizing or getting to this site.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Support<\\/h5><p>Whenever you have downloaded our item, you may get in touch with us for help through email and we will give a valiant effort to determine your issue. We will attempt to answer using the Email for more modest bug fixes, after which we will refresh the center bundle. Content help is offered to confirmed clients by Tickets as it were. Backing demands made by email and Livechat.<\\/p><p>On the off chance that your help requires extra adjustment of the System, at that point, you have two alternatives:<\\/p><ul><li>Hang tight for additional update discharge.<\\/li><li>Or on the other hand, enlist a specialist (We offer customization for extra charges).<\\/li><li><br \\/><\\/li><\\/ul><\\/div><div><h5>Ownership<\\/h5><p>You may not guarantee scholarly or selective possession of any of our items, altered or unmodified. All items are property, we created them. Our items are given \\\"with no guarantees\\\" without guarantee of any sort, either communicated or suggested. On no occasion will our juridical individual be subject to any harms including, however not restricted to, immediate, roundabout, extraordinary, accidental, or significant harms or different misfortunes emerging out of the utilization of or powerlessness to utilize our items.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Warranty<\\/h5><p>We don\'t offer any guarantee or assurance of these Services in any way. When our Services have been modified we can\'t ensure they will work with all outsider plugins, modules, or internet browsers. Program similarity ought to be tried against the show formats on the demo worker. If you don\'t mind guarantee that the programs you use will work with the component, as we can not ensure that our systems will work with all program mixes.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Unauthorized\\/Illegal Usage<\\/h5><p>You may not utilize our things for any illicit or unapproved reason or may you, in the utilization of the stage, disregard any laws in your locale (counting yet not restricted to copyright laws) just as the laws of your nation and International law. Specifically, it is disallowed to utilize the things on our foundation for pages that advance: brutality, illegal intimidation, hard sexual entertainment, bigotry, obscenity content or warez programming joins.<br \\/><br \\/>You can\'t imitate, copy, duplicate, sell, exchange or adventure any of our segment, utilization of the offered on our things, or admittance to the administration without the express composed consent by us or item proprietor.<br \\/><br \\/>Our Members are liable for all substance posted on the discussion and demo and movement that happens under your record.<br \\/><br \\/>We hold the chance of hindering your participation account quickly if we will think about a particularly not allowed conduct.<br \\/><br \\/>If you make a record on our site, you are liable for keeping up the security of your record, and you are completely answerable for all exercises that happen under the record and some other activities taken regarding the record. You should quickly inform us, of any unapproved employments of your record or some other penetrates of security.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Payment\\/Refund Policy<\\/h5><p>No refund or cash back will be made. After a deposit has been finished, it is extremely unlikely to invert it. You should utilize your equilibrium on requests our administrations, Hosting, SEO campaign. You concur that once you complete a deposit, you won\'t document a debate or a chargeback against us in any way, shape, or form.<br \\/><br \\/>If you document a debate or chargeback against us after a deposit, we claim all authority to end every single future request, prohibit you from our site. False action, for example, utilizing unapproved or taken charge cards will prompt the end of your record. There are no special cases.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Free Balance \\/ Coupon Policy<\\/h5><p>We offer numerous approaches to get FREE Balance, Coupons and Deposit offers yet we generally reserve the privilege to audit it and deduct it from your record offset with any explanation we may it is a sort of misuse. If we choose to deduct a few or all of free Balance from your record balance, and your record balance becomes negative, at that point the record will naturally be suspended. If your record is suspended because of a negative Balance you can request to make a custom payment to settle your equilibrium to actuate your record.<\\/p><\\/div>\"}', NULL, 'basic', 'terms-of-service', '2021-06-09 08:51:18', '2024-03-13 23:17:08'),
(44, 'maintenance.data', '{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color: rgb(111, 111, 111); font-family: Nunito, sans-serif; margin-bottom: 3rem !important;\\\"><h2 style=\\\"text-align:center;\\\"><font size=\\\"6\\\">We\'re just tuning up a few things.<\\/font><\\/h2><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px; font-family: Exo, sans-serif; color: rgb(54, 54, 54);\\\"><p>We apologize for the inconvenience but Front is currently undergoing planned maintenance. Thanks for your patience.<br><\\/p><\\/h3><\\/div>\",\"image\":\"6721e01e6bd921730273310.png\",\"heading\":\"THE SITE IS UNDER MAINTENANCE\"}', NULL, 'basic', NULL, '2020-07-04 23:42:52', '2024-10-30 01:28:30'),
(45, 'banner.element', '{\"badge\":\"New\",\"title\":\"Vinance to Launch POOH Deposit Contest with a Prize Pool of 10,000 USDT\",\"link\":\"user\\/register\"}', NULL, 'basic', NULL, '2023-06-17 10:38:23', '2023-08-29 12:34:45'),
(46, 'banner.element', '{\"badge\":\"New\",\"title\":\"Mystery boxes worth Up to $500 exclusively for new users!\",\"link\":\"user\\/regsiter\"}', NULL, 'basic', NULL, '2023-06-17 10:38:39', '2023-08-29 12:34:52'),
(47, 'campaign.content', '{\"title\":\"Our Ongoing Campaigns &amp; Newses\"}', NULL, 'basic', NULL, '2023-06-17 10:52:42', '2023-06-17 10:52:42'),
(48, 'campaign.element', '{\"has_image\":\"1\",\"redirect_url\":\"#\",\"image\":\"648da5bc0199e1687004604.png\"}', NULL, 'basic', NULL, '2023-06-17 10:53:24', '2023-06-17 10:53:24'),
(49, 'campaign.element', '{\"has_image\":\"1\",\"redirect_url\":\"#\",\"image\":\"648da5c4a31ab1687004612.png\"}', NULL, 'basic', NULL, '2023-06-17 10:53:32', '2023-06-17 10:53:32'),
(50, 'campaign.element', '{\"has_image\":\"1\",\"redirect_url\":\"#\",\"image\":\"648da5cd1dad51687004621.png\"}', NULL, 'basic', NULL, '2023-06-17 10:53:41', '2023-06-17 10:53:41'),
(51, 'campaign.element', '{\"has_image\":\"1\",\"redirect_url\":\"#\",\"image\":\"648da5d72f1001687004631.png\"}', NULL, 'basic', NULL, '2023-06-17 10:53:51', '2023-06-17 10:53:51'),
(52, 'campaign.element', '{\"has_image\":\"1\",\"redirect_url\":\"#\",\"image\":\"648da6dbeadbf1687004891.png\"}', NULL, 'basic', NULL, '2023-06-17 10:58:11', '2023-06-17 10:58:11'),
(53, 'choose_us.content', '{\"heading\":\"Why Vinance ?\",\"has_image\":\"1\",\"shape_image\":\"648dae2a2ab661687006762.png\"}', NULL, 'basic', NULL, '2023-06-17 11:16:20', '2023-06-17 11:29:22'),
(54, 'choose_us.element', '{\"icon\":\"<i class=\\\"fas fa-headphones-alt\\\"><\\/i>\",\"heading\":\"24\\/7 Customer Service\",\"subheading\":\"Contact Vinance customer support with your questions at any time.\"}', NULL, 'basic', NULL, '2023-06-17 11:16:52', '2023-06-17 11:26:37'),
(55, 'choose_us.element', '{\"icon\":\"<i class=\\\"far fa-user\\\"><\\/i>\",\"heading\":\"Prime Membership\",\"subheading\":\"We offer Prime Membership with our dedeciated trading expert.\"}', NULL, 'basic', NULL, '2023-06-17 11:17:53', '2023-08-29 00:31:38'),
(56, 'choose_us.element', '{\"icon\":\"<i class=\\\"fas fa-users\\\"><\\/i>\",\"heading\":\"Vinance Community\",\"subheading\":\"There is an excellent team behind vinance, and we adore communicating with our users!\"}', NULL, 'basic', NULL, '2023-06-17 11:18:08', '2023-08-29 00:29:24'),
(57, 'choose_us.element', '{\"icon\":\"<i class=\\\"fas fa-graduation-cap\\\"><\\/i>\",\"heading\":\"Vinance Academy\",\"subheading\":\"Contact Vinance customer support with your questions at any time.\"}', NULL, 'basic', NULL, '2023-06-17 11:18:42', '2023-06-17 11:25:57'),
(58, 'how_to_invest.content', '{\"has_image\":\"1\",\"heading\":\"How to Invest ?\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\",\"button_text\":\"Get Start\",\"button_link\":\"user\\/register\",\"image_light\":\"648db7736066c1687009139.png\",\"image_dark\":\"648db77371abe1687009139.png\"}', NULL, 'basic', NULL, '2023-06-17 11:54:26', '2023-12-13 19:11:23'),
(59, 'how_to_invest.element', '{\"heading\":\"Sign Up On Vinance\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\"}', NULL, 'basic', NULL, '2023-06-17 12:01:30', '2023-06-17 12:01:30'),
(60, 'how_to_invest.element', '{\"heading\":\"Verified Your Account\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\"}', NULL, 'basic', NULL, '2023-06-17 12:01:45', '2023-08-24 06:35:04'),
(61, 'how_to_invest.element', '{\"heading\":\"Deposit \\/ Buy Crypto\",\"subheading\":\"Fund your cryptocurrency account to begin trading. A wide variety of payment methods are supported.\"}', NULL, 'basic', NULL, '2023-06-17 12:01:55', '2023-06-17 12:01:55'),
(62, 'how_to_invest.element', '{\"heading\":\"Trade\",\"subheading\":\"Start buying and selling cryptocurrencies, and explore even more KuCoin products and services!\"}', NULL, 'basic', NULL, '2023-06-17 12:02:04', '2023-06-17 12:02:04'),
(63, 'crypto_currency.content', '{\"heading\":\"Our Crypto Currency\",\"subheading\":\"Vinance is offering world-famous cryptocurrencies.  We serve our members with products built in a fairer, more accessible, and efficient manner.\"}', NULL, 'basic', NULL, '2023-06-17 12:21:26', '2023-09-05 11:25:24'),
(64, 'product.content', '{\"heading\":\"Our Ultimate Products\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\",\"has_image\":\"1\",\"image_light\":\"648dbd4f0b6951687010639.png\",\"image_dark\":\"648dbd4f2094d1687010639.png\"}', NULL, 'basic', NULL, '2023-06-17 12:33:59', '2023-06-17 12:33:59'),
(65, 'product.element', '{\"icon\":\"<i class=\\\"fas fa-exchange-alt\\\"><\\/i>\",\"heading\":\"Trade\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\"}', NULL, 'basic', NULL, '2023-06-17 12:36:45', '2023-08-24 07:20:54'),
(66, 'product.element', '{\"icon\":\"<i class=\\\"fas fa-coins\\\"><\\/i>\",\"heading\":\"Crypto Currency\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\"}', NULL, 'basic', NULL, '2023-06-17 12:36:56', '2023-08-24 07:21:32'),
(67, 'product.element', '{\"icon\":\"<i class=\\\"fas fa-box\\\"><\\/i>\",\"heading\":\"Vinance Affiliate\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\"}', NULL, 'basic', NULL, '2023-06-17 12:37:16', '2023-08-24 07:19:43'),
(68, 'faq.content', '{\"heading\":\"Frequently Asked Questions\",\"subheading\":\"Start trading to get up to $500 in Mystery Box prizes!\",\"has_image\":\"1\",\"image_light\":\"648e8d9b941781687063963.png\",\"image_dark\":\"648e8d9be35f01687063963.png\"}', NULL, 'basic', NULL, '2023-06-18 03:15:37', '2023-06-18 03:22:44'),
(69, 'faq.element', '{\"question\":\"How to Complete Identity Verification?\",\"answer\":\"Vinance provides a very easy system for account verification. First, create your account & log in to your dashboard. Then submit your required data for completed account verification. Be careful, all information you are provided is real & true.\"}', NULL, 'basic', NULL, '2023-06-18 03:16:37', '2023-08-29 00:44:00'),
(70, 'faq.element', '{\"question\":\"How to buy cryptocurrency on the vinance?\",\"answer\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\"}', NULL, 'basic', NULL, '2023-06-18 03:16:48', '2023-08-29 00:33:12'),
(71, 'faq.element', '{\"question\":\"Can I buy cryptocurrency with a credit card?\",\"answer\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\"}', NULL, 'basic', NULL, '2023-06-18 03:16:56', '2023-08-29 00:37:08'),
(72, 'faq.element', '{\"question\":\"Why is it better to trade cryptocurrencies on vinance?\",\"answer\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\"}', NULL, 'basic', NULL, '2023-06-18 03:17:06', '2023-08-29 00:37:39'),
(73, 'faq.element', '{\"question\":\"What products does Vinance Ecosystem include?\",\"answer\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\"}', NULL, 'basic', NULL, '2023-06-18 03:17:14', '2023-08-29 00:38:09'),
(74, 'subscribe.content', '{\"heading\":\"Get Started For Free\",\"subheading\":\"Subscribe our newsletter &amp; Stay Update Every Day\",\"button_text\":\"Submit\",\"placeholder\":\"Enter you email\",\"has_image\":\"1\",\"image\":\"648e95af074e21687066031.png\",\"shape_image_one\":\"648e95af142521687066031.png\",\"shape_image_two\":\"648e95af162191687066031.png\"}', NULL, 'basic', NULL, '2023-06-18 03:48:21', '2023-06-18 03:57:11'),
(75, 'login.content', '{\"heading_one\":\"Welcome To Vinance\",\"subheading_one\":\"Login to embrace the possibilities\",\"heading_two\":\"Log In\",\"subheading_two\":\"Securely connect to your account\",\"has_image\":\"1\",\"image\":\"648ea891e68a91687070865.png\"}', NULL, 'basic', NULL, '2023-06-18 05:13:22', '2023-07-09 10:12:37'),
(76, 'register.content', '{\"title\":\"New User Rewards\",\"heading_one\":\"Sign up to earn {{$200}} USDT\",\"subheading_one\":\"Claim Your Reward\",\"heading_two\":\"Sign Up\",\"subheading_two\":\"Join the community and unleash endless possibilities\",\"has_image\":\"1\",\"image\":\"648ec5b656f6a1687078326.png\"}', NULL, 'basic', NULL, '2023-06-18 07:22:06', '2023-07-09 10:22:53'),
(77, 'kyc_content.content', '{\"unverified_content\":\"Dear User, we need your KYC Data for some action. Don\'t hesitate to provide KYC Data, It\'s so much potential for us too. Don\'t worry,  it\'s very much secure in our system.\",\"pending_content\":\"Dear user, Your submitted KYC Data is currently pending now. Please take some time to review your Data. Thank you so much for your cooperation.\"}', NULL, 'basic', NULL, '2023-07-08 09:29:51', '2023-07-08 09:29:51'),
(78, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Auto-Invest Adds PENDLE: Subscribe to Auto-Invest to Get Up to $2,000 in BTC Token\",\"description_nic\":\"<div>\\n\\n<h3><font size=\\\"4\\\">The standard Lorem Ipsum passage, used since the 1500s<\\/font><\\/h3><p>\\\"Lorem\\n ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \\nmollit anim id est laborum.\\\"<\\/p><p><br \\/><\\/p><h3><font size=\\\"4\\\">Section 1.10.32 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3><p>\\\"Sed\\n ut perspiciatis unde omnis iste natus error sit voluptatem accusantium \\ndoloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo \\ninventore veritatis et quasi architecto beatae vitae dicta sunt \\nexplicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut \\nodit aut fugit, sed quia consequuntur magni dolores eos qui ratione \\nvoluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum \\nquia dolor sit amet, consectetur, adipisci velit, sed quia non numquam \\neius modi tempora incidunt ut labore et dolore magnam aliquam quaerat \\nvoluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam \\ncorporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?\\n Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse \\nquam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo \\nvoluptas nulla pariatur?\\\".<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"But I must explain to you how all this mistaken idea of denouncing \\npleasure and praising pain was born and I will give you a complete \\naccount of the system, and expound the actual teachings of the great \\nexplorer of the truth, the master-builder of human happiness. No one \\nrejects, dislikes, or avoids pleasure itself, because it is pleasure, \\nbut because those who do not know how to pursue pleasure rationally \\nencounter consequences that are extremely painful. Nor again is there \\nanyone who loves or pursues or desires to obtain pain of itself, because\\n it is pain, but because occasionally circumstances occur in which toil \\nand pain can procure him some great pleasure. To take a trivial example,\\n which of us ever undertakes laborious physical exercise, except to \\nobtain some advantage from it? But who has any right to find fault with a\\n man who chooses to enjoy a pleasure that has no annoying consequences, \\nor one who avoids a pain that produces no resultant pleasure?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">Section 1.10.33 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3>\\n<p>\\\"At vero eos et accusamus et iusto odio dignissimos ducimus qui \\nblanditiis praesentium voluptatum deleniti atque corrupti quos dolores \\net quas molestias excepturi sint occaecati cupiditate non provident, \\nsimilique sunt in culpa qui officia deserunt mollitia animi, id est \\nlaborum et dolorum fuga. Et harum quidem rerum facilis est et expedita \\ndistinctio. Nam libero tempore, cum soluta nobis est eligendi optio \\ncumque nihil impedit quo minus id quod maxime placeat facere possimus, \\nomnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem \\nquibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet\\n ut et voluptates repudiandae sint et molestiae non recusandae. Itaque \\nearum rerum hic tenetur a sapiente delectus, ut aut reiciendis \\nvoluptatibus maiores alias consequatur aut perferendis doloribus \\nasperiores repellat.\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"On the other hand, we denounce with righteous indignation and \\ndislike men who are so beguiled and demoralized by the charms of \\npleasure of the moment, so blinded by desire, that they cannot foresee \\nthe pain and trouble that are bound to ensue; and equal blame belongs to\\n those who fail in their duty through weakness of will, which is the \\nsame as saying through shrinking from toil and pain. These cases are \\nperfectly simple and easy to distinguish. In a free hour, when our power\\n of choice is untrammelled and when nothing prevents our being able to \\ndo what we like best, every pleasure is to be welcomed and every pain \\navoided. But in certain circumstances and owing to the claims of duty or\\n the obligations of business it will frequently occur that pleasures \\nhave to be repudiated and annoyances accepted. The wise man therefore \\nalways holds in these matters to this principle of selection: he rejects\\n pleasures to secure other greater pleasures, or else he endures pains \\nto avoid worse pains.\\\"<\\/p>\\n<\\/div>\",\"image\":\"64eaee4382f0d1693118019.png\"}', NULL, 'basic', 'auto-invest-adds-pendle:-subscribe-to-auto-invest-to-get-up-to-$2000-in-btc-token', '2023-07-10 07:38:58', '2023-08-29 13:43:55'),
(79, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Notice on the Removal of Trading Bots Services from Selected Spot Trading Pairs\",\"description_nic\":\"<div>\\n\\n<h3><font size=\\\"4\\\">The standard Lorem Ipsum passage, used since the 1500s<\\/font><\\/h3><p>\\\"Lorem\\n ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \\nmollit anim id est laborum.\\\"<\\/p><p><br \\/><\\/p><h3><font size=\\\"4\\\">Section 1.10.32 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3><p>\\\"Sed\\n ut perspiciatis unde omnis iste natus error sit voluptatem accusantium \\ndoloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo \\ninventore veritatis et quasi architecto beatae vitae dicta sunt \\nexplicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut \\nodit aut fugit, sed quia consequuntur magni dolores eos qui ratione \\nvoluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum \\nquia dolor sit amet, consectetur, adipisci velit, sed quia non numquam \\neius modi tempora incidunt ut labore et dolore magnam aliquam quaerat \\nvoluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam \\ncorporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?\\n Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse \\nquam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo \\nvoluptas nulla pariatur?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"But I must explain to you how all this mistaken idea of denouncing \\npleasure and praising pain was born and I will give you a complete \\naccount of the system, and expound the actual teachings of the great \\nexplorer of the truth, the master-builder of human happiness. No one \\nrejects, dislikes, or avoids pleasure itself, because it is pleasure, \\nbut because those who do not know how to pursue pleasure rationally \\nencounter consequences that are extremely painful. Nor again is there \\nanyone who loves or pursues or desires to obtain pain of itself, because\\n it is pain, but because occasionally circumstances occur in which toil \\nand pain can procure him some great pleasure. To take a trivial example,\\n which of us ever undertakes laborious physical exercise, except to \\nobtain some advantage from it? But who has any right to find fault with a\\n man who chooses to enjoy a pleasure that has no annoying consequences, \\nor one who avoids a pain that produces no resultant pleasure?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">Section 1.10.33 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3>\\n<p>\\\"At vero eos et accusamus et iusto odio dignissimos ducimus qui \\nblanditiis praesentium voluptatum deleniti atque corrupti quos dolores \\net quas molestias excepturi sint occaecati cupiditate non provident, \\nsimilique sunt in culpa qui officia deserunt mollitia animi, id est \\nlaborum et dolorum fuga. Et harum quidem rerum facilis est et expedita \\ndistinctio. Nam libero tempore, cum soluta nobis est eligendi optio \\ncumque nihil impedit quo minus id quod maxime placeat facere possimus, \\nomnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem \\nquibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet\\n ut et voluptates repudiandae sint et molestiae non recusandae. Itaque \\nearum rerum hic tenetur a sapiente delectus, ut aut reiciendis \\nvoluptatibus maiores alias consequatur aut perferendis doloribus \\nasperiores repellat.\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"On the other hand, we denounce with righteous indignation and \\ndislike men who are so beguiled and demoralized by the charms of \\npleasure of the moment, so blinded by desire, that they cannot foresee \\nthe pain and trouble that are bound to ensue; and equal blame belongs to\\n those who fail in their duty through weakness of will, which is the \\nsame as saying through shrinking from toil and pain. These cases are \\nperfectly simple and easy to distinguish. In a free hour, when our power\\n of choice is untrammelled and when nothing prevents our being able to \\ndo what we like best, every pleasure is to be welcomed and every pain \\navoided. But in certain circumstances and owing to the claims of duty or\\n the obligations of business it will frequently occur that pleasures \\nhave to be repudiated and annoyances accepted. The wise man therefore \\nalways holds in these matters to this principle of selection: he rejects\\n pleasures to secure other greater pleasures, or else he endures pains \\nto avoid worse pains.\\\"<\\/p>\\n<\\/div>\",\"image\":\"64eaedbb155211693117883.png\"}', NULL, 'basic', 'notice-on-the-removal-of-trading-bots-services-from-selected-spot-trading-pairs', '2023-07-10 07:39:10', '2023-08-29 14:44:36'),
(80, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Updates on Zero-Fee Bitcoin Trading\",\"description_nic\":\"<div>\\n\\n<h3><font size=\\\"4\\\">The standard Lorem Ipsum passage, used since the 1500s<\\/font><\\/h3><p>\\\"Lorem\\n ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \\nmollit anim id est laborum.\\\"<\\/p><p><br \\/><\\/p><h3><font size=\\\"4\\\">Section 1.10.32 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3><p>\\\"Sed\\n ut perspiciatis unde omnis iste natus error sit voluptatem accusantium \\ndoloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo \\ninventore veritatis et quasi architecto beatae vitae dicta sunt \\nexplicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut \\nodit aut fugit, sed quia consequuntur magni dolores eos qui ratione \\nvoluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum \\nquia dolor sit amet, consectetur, adipisci velit, sed quia non numquam \\neius modi tempora incidunt ut labore et dolore magnam aliquam quaerat \\nvoluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam \\ncorporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?\\n Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse \\nquam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo \\nvoluptas nulla pariatur?\\\"<\\/p><p><font size=\\\"4\\\"><br \\/><\\/font><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"But I must explain to you how all this mistaken idea of denouncing \\npleasure and praising pain was born and I will give you a complete \\naccount of the system, and expound the actual teachings of the great \\nexplorer of the truth, the master-builder of human happiness. No one \\nrejects, dislikes, or avoids pleasure itself, because it is pleasure, \\nbut because those who do not know how to pursue pleasure rationally \\nencounter consequences that are extremely painful. Nor again is there \\nanyone who loves or pursues or desires to obtain pain of itself, because\\n it is pain, but because occasionally circumstances occur in which toil \\nand pain can procure him some great pleasure. To take a trivial example,\\n which of us ever undertakes laborious physical exercise, except to \\nobtain some advantage from it? But who has any right to find fault with a\\n man who chooses to enjoy a pleasure that has no annoying consequences, \\nor one who avoids a pain that produces no resultant pleasure?\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">Section 1.10.33 of \\\"de Finibus Bonorum et Malorum\\\", written by Cicero in 45 BC<\\/font><\\/h3>\\n<p>\\\"At vero eos et accusamus et iusto odio dignissimos ducimus qui \\nblanditiis praesentium voluptatum deleniti atque corrupti quos dolores \\net quas molestias excepturi sint occaecati cupiditate non provident, \\nsimilique sunt in culpa qui officia deserunt mollitia animi, id est \\nlaborum et dolorum fuga. Et harum quidem rerum facilis est et expedita \\ndistinctio. Nam libero tempore, cum soluta nobis est eligendi optio \\ncumque nihil impedit quo minus id quod maxime placeat facere possimus, \\nomnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem \\nquibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet\\n ut et voluptates repudiandae sint et molestiae non recusandae. Itaque \\nearum rerum hic tenetur a sapiente delectus, ut aut reiciendis \\nvoluptatibus maiores alias consequatur aut perferendis doloribus \\nasperiores repellat.\\\"<\\/p><p><br \\/><\\/p>\\n<h3><font size=\\\"4\\\">1914 translation by H. Rackham<\\/font><\\/h3>\\n<p>\\\"On the other hand, we denounce with righteous indignation and \\ndislike men who are so beguiled and demoralized by the charms of \\npleasure of the moment, so blinded by desire, that they cannot foresee \\nthe pain and trouble that are bound to ensue; and equal blame belongs to\\n those who fail in their duty through weakness of will, which is the \\nsame as saying through shrinking from toil and pain. These cases are \\nperfectly simple and easy to distinguish. In a free hour, when our power\\n of choice is untrammelled and when nothing prevents our being able to \\ndo what we like best, every pleasure is to be welcomed and every pain \\navoided. But in certain circumstances and owing to the claims of duty or\\n the obligations of business it will frequently occur that pleasures \\nhave to be repudiated and annoyances accepted. The wise man therefore \\nalways holds in these matters to this principle of selection: he rejects\\n pleasures to secure other greater pleasures, or else he endures pains \\nto avoid worse pains.\\\"<\\/p>\\n<\\/div>\",\"image\":\"64eaed5b147121693117787.png\"}', NULL, 'basic', 'updates-on-zero-fee-bitcoin-trading', '2023-07-10 07:39:25', '2023-08-29 14:43:13'),
(81, 'footer.content', '{\"about_info\":\"We\'re passionate about creating unforgettable moments. Our platform provides a seamless and transparent trading experience, with a range of exciting crypto and fiat currency\"}', NULL, 'basic', NULL, '2023-07-15 03:59:36', '2023-08-24 07:26:28'),
(82, 'social_icon.element', '{\"title\":\"Twitter\",\"icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/twitter.com\\/\"}', NULL, 'basic', NULL, '2023-07-15 04:06:41', '2023-07-15 04:06:41'),
(83, 'social_icon.element', '{\"title\":\"instagram\",\"icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', NULL, 'basic', NULL, '2023-07-15 04:07:16', '2023-07-15 04:07:16'),
(84, 'social_icon.element', '{\"title\":\"telegram\",\"icon\":\"<i class=\\\"fab fa-telegram\\\"><\\/i>\",\"url\":\"https:\\/\\/telegram.org\\/\"}', NULL, 'basic', NULL, '2023-07-15 04:08:00', '2023-07-15 04:08:00'),
(85, 'account_recovery.content', '{\"title\":\"Account access restored\",\"heading\":\"Recover password by simple steps\",\"has_image\":\"1\",\"image\":\"64b23f7a69a9a1689403258.png\"}', NULL, 'basic', NULL, '2023-07-15 05:10:58', '2023-07-15 05:15:24'),
(86, 'account_verification.content', '{\"title\":\"Account verification\",\"heading\":\"Secure and simple account verification process\",\"has_image\":\"1\",\"image\":\"64b25210d62e91689408016.png\"}', NULL, 'basic', NULL, '2023-07-15 06:30:16', '2023-07-15 06:30:17');
INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(87, 'policy_pages.element', '{\"title\":\"Trade Policy\",\"details\":\"<br \\/><div><h5>Terms & Conditions for Users<\\/h5><p>Before getting to this site, you are consenting to be limited by these site Terms and Conditions of Use, every single appropriate law, and guidelines, and concur that you are answerable for consistency with any material neighborhood laws. If you disagree with any of these terms, you are restricted from utilizing or getting to this site.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Support<\\/h5><p>Whenever you have downloaded our item, you may get in touch with us for help through email and we will give a valiant effort to determine your issue. We will attempt to answer using the Email for more modest bug fixes, after which we will refresh the center bundle. Content help is offered to confirmed clients by Tickets as it were. Backing demands made by email and Livechat.<\\/p><p>On the off chance that your help requires extra adjustment of the System, at that point, you have two alternatives:<\\/p><ul><li>Hang tight for additional update discharge.<\\/li><li>Or on the other hand, enlist a specialist (We offer customization for extra charges).<\\/li><li><br \\/><\\/li><\\/ul><\\/div><div><h5>Ownership<\\/h5><p>You may not guarantee scholarly or selective possession of any of our items, altered or unmodified. All items are property, we created them. Our items are given \\\"with no guarantees\\\" without guarantee of any sort, either communicated or suggested. On no occasion will our juridical individual be subject to any harms including, however not restricted to, immediate, roundabout, extraordinary, accidental, or significant harms or different misfortunes emerging out of the utilization of or powerlessness to utilize our items.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Warranty<\\/h5><p>We don\'t offer any guarantee or assurance of these Services in any way. When our Services have been modified we can\'t ensure they will work with all outsider plugins, modules, or internet browsers. Program similarity ought to be tried against the show formats on the demo worker. If you don\'t mind guarantee that the programs you use will work with the component, as we can not ensure that our systems will work with all program mixes.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Unauthorized\\/Illegal Usage<\\/h5><p>You may not utilize our things for any illicit or unapproved reason or may you, in the utilization of the stage, disregard any laws in your locale (counting yet not restricted to copyright laws) just as the laws of your nation and International law. Specifically, it is disallowed to utilize the things on our foundation for pages that advance: brutality, illegal intimidation, hard sexual entertainment, bigotry, obscenity content or warez programming joins.<br \\/><br \\/>You can\'t imitate, copy, duplicate, sell, exchange or adventure any of our segment, utilization of the offered on our things, or admittance to the administration without the express composed consent by us or item proprietor.<br \\/><br \\/>Our Members are liable for all substance posted on the discussion and demo and movement that happens under your record.<br \\/><br \\/>We hold the chance of hindering your participation account quickly if we will think about a particularly not allowed conduct.<br \\/><br \\/>If you make a record on our site, you are liable for keeping up the security of your record, and you are completely answerable for all exercises that happen under the record and some other activities taken regarding the record. You should quickly inform us, of any unapproved employments of your record or some other penetrates of security.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Payment\\/Refund Policy<\\/h5><p>No refund or cash back will be made. After a deposit has been finished, it is extremely unlikely to invert it. You should utilize your equilibrium on requests our administrations, Hosting, SEO campaign. You concur that once you complete a deposit, you won\'t document a debate or a chargeback against us in any way, shape, or form.<br \\/><br \\/>If you document a debate or chargeback against us after a deposit, we claim all authority to end every single future request, prohibit you from our site. False action, for example, utilizing unapproved or taken charge cards will prompt the end of your record. There are no special cases.<\\/p><p><br \\/><\\/p><\\/div><div><h5>Free Balance \\/ Coupon Policy<\\/h5><p>We offer numerous approaches to get FREE Balance, Coupons and Deposit offers yet we generally reserve the privilege to audit it and deduct it from your record offset with any explanation we may it is a sort of misuse. If we choose to deduct a few or all of free Balance from your record balance, and your record balance becomes negative, at that point the record will naturally be suspended. If your record is suspended because of a negative Balance you can request to make a custom payment to settle your equilibrium to actuate your record.<\\/p><\\/div>\"}', NULL, 'basic', 'trade-policy', '2023-08-28 22:23:56', '2024-03-13 23:17:46'),
(88, 'p2p_how_to_work.element', '{\"heading\":\"Place an Order\",\"icon\":\"<i class=\\\"fas fa-clipboard-list\\\"><\\/i>\",\"small_description\":\"Once you place a P2P order, the crypto asset will be escrowed by Viserlab P2P.\",\"buy_or_sell\":\"buy\"}', NULL, 'basic', NULL, '2023-11-23 05:28:25', '2023-11-23 05:59:41'),
(89, 'p2p_how_to_work.element', '{\"heading\":\"Pay the Seller\",\"icon\":\"<i class=\\\"fas fa-clipboard-list\\\"><\\/i>\",\"small_description\":\"Send money to the seller via the suggested payment methods. Complete the fiat transaction and click \\\"Transferred, notify seller\\\" on Viserlab P2P.\",\"buy_or_sell\":\"buy\"}', NULL, 'basic', NULL, '2023-11-23 05:28:08', '2023-11-23 05:59:38'),
(90, 'p2p_how_to_work.element', '{\"heading\":\"Get your Crypto\",\"icon\":\"<i class=\\\"fas fa-clipboard-list\\\"><\\/i>\",\"small_description\":\"Once the seller confirms receipt of money, the escrowed crypto will be released to you.\",\"buy_or_sell\":\"buy\"}', NULL, 'basic', NULL, '2023-11-23 05:27:37', '2023-11-23 05:59:34'),
(91, 'p2p_how_to_work.element', '{\"heading\":\"Get your Crypto\",\"icon\":\"<i class=\\\"fas fa-coins\\\"><\\/i>\",\"small_description\":\"Once the seller confirms receipt of money, the escrowed crypto will be released to you.\",\"buy_or_sell\":\"sell\"}', NULL, 'basic', NULL, '2023-11-23 05:27:04', '2023-11-23 05:59:30'),
(92, 'p2p_how_to_work.element', '{\"heading\":\"Confirm the Payment\",\"icon\":\"<i class=\\\"far fa-check-circle\\\"><\\/i>\",\"small_description\":\"Send money to the seller via the suggested payment methods. Complete the fiat transaction and click \\\"Transferred, notify seller\\\" on Viserlab P2P.\",\"buy_or_sell\":\"sell\"}', NULL, 'basic', NULL, '2023-11-23 05:26:40', '2023-11-23 05:59:26'),
(93, 'p2p_how_to_work.element', '{\"heading\":\"Create an Order\",\"icon\":\"<i class=\\\"fas fa-clipboard-list\\\"><\\/i>\",\"small_description\":\"Place an Order and it will be listed on Cryptomus P2P Exchange page. The funds will be escrowed by the Exchange.\",\"buy_or_sell\":\"sell\"}', NULL, 'basic', NULL, '2023-11-23 05:23:47', '2023-11-23 05:59:23'),
(94, 'p2p_how_to_work.content', '{\"heading\":\"How {{ P2P Works}}\"}', NULL, 'basic', NULL, '2023-11-23 05:23:12', '2023-11-23 05:51:14'),
(95, 'p2p_banner.content', '{\"has_image\":\"1\",\"heading\":\"Trade Smarter Together: The {{P2P}} Trading Revolution Begins.\",\"subheading\":\"Buy and sell on P2P using your preferred payment\",\"image_one\":\"655e0355e3e7e1700660053.png\",\"image_two\":\"655e03562fb081700660054.png\"}', NULL, 'basic', NULL, '2023-11-22 12:32:28', '2023-11-22 12:34:14'),
(97, 'app_onboarding.element', '{\"has_image\":[\"1\"],\"title\":\"Best Cryptocurrency Marketplace\",\"subtitle\":\"Explore a dynamic crypto trading platform with diverse assets, competitive rates, and advanced features for optimal opportunities.\",\"image\":\"660933d6384f31711879126.png\"}', NULL, 'basic', NULL, '2024-03-31 03:58:46', '2024-03-31 03:58:46'),
(98, 'app_onboarding.element', '{\"has_image\":[\"1\"],\"title\":\"Trade Anywhere Anytime\",\"subtitle\":\"Trade on the go with our mobile-friendly platform\\u2014seize opportunities anytime, anywhere, and take control of your financial journey with ease.\",\"image\":\"660933ea23fa51711879146.png\"}', NULL, 'basic', NULL, '2024-03-31 03:59:06', '2024-03-31 03:59:06'),
(99, 'app_onboarding.element', '{\"has_image\":[\"1\"],\"title\":\"Transact Fast and Easy\",\"subtitle\":\"Experience swift, hassle-free transactions on our platform with an intuitive interface designed for today\'s fast-paced financial landscape.\",\"image\":\"660933fb40aad1711879163.png\"}', NULL, 'basic', NULL, '2024-03-31 03:59:23', '2024-03-31 03:59:23'),
(100, 'register_disable.content', '{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Page you are looking for doesn\'t exit or an other error occurred or temporarily unavailable.\",\"button_name\":\"Go to Home\",\"button_url\":\"\\/\",\"image\":\"66488b6067df71716030304.png\"}', NULL, 'basic', '', '2024-05-18 05:05:04', '2024-05-18 05:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `future_orders`
--

CREATE TABLE `future_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) DEFAULT 0,
  `future_trade_config_id` int(10) DEFAULT 0,
  `future_queue_order_id` int(10) NOT NULL DEFAULT 0,
  `coin_id` int(10) DEFAULT 0,
  `market_currency_id` int(10) DEFAULT 0,
  `margin` decimal(28,8) DEFAULT 0.00000000,
  `size` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `coin_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `leverage` int(11) NOT NULL DEFAULT 0,
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `liquidation_rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `margin_mode` tinyint(1) DEFAULT 0 COMMENT '1: cross\r\n2: isolated',
  `order_side` tinyint(1) DEFAULT 0,
  `order_type` tinyint(1) DEFAULT 0,
  `pnl` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `future_queue_orders`
--

CREATE TABLE `future_queue_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `future_order_id` int(10) DEFAULT 0,
  `future_trade_config_id` int(10) NOT NULL DEFAULT 0,
  `user_id` int(10) DEFAULT 0,
  `pair_id` int(10) DEFAULT 0,
  `coin_id` int(10) DEFAULT 0,
  `market_currency_id` int(10) DEFAULT 0,
  `order_side` tinyint(1) DEFAULT 0,
  `order_type` tinyint(1) DEFAULT 0,
  `size` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `remaining_size` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `coin_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `remaining_coin_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `margin` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `remaining_margin` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `margin_mode` tinyint(1) NOT NULL DEFAULT 0,
  `leverage` int(11) NOT NULL DEFAULT 0,
  `rate` decimal(28,8) DEFAULT 0.00000000,
  `stop_rate` decimal(28,8) DEFAULT 0.00000000,
  `type` tinyint(1) DEFAULT 0 COMMENT '1: Market Order\r\n2: Limit Order\r\n3: Stop Limit Ordder\r\n4: Take Profit/Loss\r\n',
  `last_update` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `future_trades`
--

CREATE TABLE `future_trades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) DEFAULT 0,
  `future_order_id` int(10) DEFAULT 0,
  `future_trade_config_id` int(10) DEFAULT 0,
  `trade_side` tinyint(1) NOT NULL DEFAULT 0,
  `rate` decimal(28,8) DEFAULT 0.00000000,
  `amount` decimal(28,8) DEFAULT 0.00000000,
  `total` decimal(28,8) DEFAULT 0.00000000,
  `charge` decimal(28,8) DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `future_trade_configs`
--

CREATE TABLE `future_trade_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pair_id` int(10) DEFAULT 0,
  `min_buy_amount` decimal(28,8) DEFAULT 0.00000000,
  `max_buy_amount` decimal(28,8) DEFAULT 0.00000000,
  `min_sell_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `max_sell_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `buy_charge` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sell_charge` decimal(5,2) NOT NULL DEFAULT 0.00,
  `leverage` int(11) DEFAULT 0,
  `maintenance_margin_rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `is_default` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `code` int(11) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `alias` varchar(40) NOT NULL DEFAULT 'NULL',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text DEFAULT NULL,
  `supported_currencies` text DEFAULT NULL,
  `crypto` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal', 'Paypal', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-owud61543012@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:04:38'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 01:35:33'),
(3, 0, 103, 'Stripe Hosted', 'Stripe', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:48:36'),
(4, 0, 104, 'Skrill', 'Skrill', 1, '{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 01:30:16'),
(5, 0, 105, 'PayTM', 'Paytm', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 03:00:44'),
(6, 0, 106, 'Payeer', 'Payeer', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 13:14:22', '2022-08-28 10:11:14'),
(7, 0, 107, 'PayStack', 'Paystack', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_cd330608eb47970889bca397ced55c1dd5ad3783\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_8a0b1f199362d7acc9c390bff72c4e81f74e2ac3\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 13:14:22', '2021-05-21 01:49:51'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-06-05 11:37:45'),
(10, 0, 110, 'RazorPay', 'Razorpay', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:51:32'),
(11, 0, 111, 'Stripe Storefront', 'StripeJs', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 00:53:10'),
(12, 0, 112, 'Instamojo', 'Instamojo', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:56:20'),
(13, 0, 501, 'Blockchain', 'Blockchain', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}', '{\"BTC\":\"BTC\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2022-03-21 07:41:56'),
(15, 0, 503, 'CoinPayments', 'Coinpayments', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2023-04-08 03:17:18'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:07:44'),
(17, 0, 505, 'Coingate', 'Coingate', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2022-03-30 09:24:57'),
(18, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 13:14:22', '2021-05-21 02:02:47'),
(24, 0, 113, 'Paypal Express', 'PaypalSdk', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-20 23:01:08'),
(25, 0, 114, 'Stripe Checkout', 'StripeV3', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 13:14:22', '2021-05-21 00:58:38'),
(27, 0, 115, 'Mollie', 'Mollie', 1, '{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"vi@gmail.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2021-05-21 02:44:45'),
(30, 0, 116, 'Cashmaal', 'Cashmaal', 1, '{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}', '{\"PKR\":\"PKR\",\"USD\":\"USD\"}', 0, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}', NULL, NULL, '2021-06-22 08:05:04'),
(36, 0, 119, 'Mercado Pago', 'MercadoPago', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\",\"ARS\":\"ARS\",\"BRL\":\"BRL\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"MXN\":\"MXN\",\"PEN\":\"PEN\",\"UYU\":\"UYU\",\"VEF\":\"VEF\",\"BOB\":\"BOB\"}', 0, NULL, NULL, NULL, '2022-09-14 07:41:14'),
(37, 0, 120, 'Authorize.net', 'Authorize', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2022-08-28 09:33:06'),
(46, 0, 121, 'NMI', 'NMI', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}', '{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, NULL, '2022-08-28 10:32:31'),
(50, 0, 507, 'BTCPay', 'BTCPay', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"HsqFVTXSeUFJu7caoYZc3CTnP8g5LErVdHhEXPVTheHf\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"4436bd706f99efae69305e7c4eff4780de1335ce\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2023-02-14 04:42:09'),
(51, 0, 508, 'Now payments hosted', 'NowPaymentsHosted', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2023-02-14 05:08:23'),
(52, 0, 509, 'Now payments checkout', 'NowPaymentsCheckout', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"---------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2023-02-14 05:08:04'),
(53, 0, 122, '2Checkout', 'TwoCheckout', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2023-04-29 09:21:58'),
(54, 0, 123, 'Checkout', 'Checkout', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(64, 0, 510, 'Binance', 'Binance', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2023-02-14 05:08:04'),
(65, 0, 124, 'SslCommerz', 'SslCommerz', 1, '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"store_password\": {\"title\": \"Store Password\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(66, 0, 125, 'Aamarpay', 'Aamarpay', 1, '{\"store_id\": {\"title\": \"Store ID\",\"global\": true,\"value\": \"---------\"},\"signature_key\": {\"title\": \"Signature Key\",\"global\": true,\"value\": \"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2023-05-06 07:43:01'),
(67, 0, 126, 'bKash', 'BKash', 1, '{\"username\":{\"title\":\"Username\",\"global\":true,\"value\":\"01770618567\"},\"password\":{\"title\":\"Password\",\"global\":true,\"value\":\"D7DaC<*E*eG\"},\"app_key\":{\"title\":\"App Key\",\"global\":true,\"value\":\"0vWQuCRGiUX7EPVjQDr0EUAYtc\"},\"app_secret\":{\"title\":\"App Secret\",\"global\":true,\"value\":\"jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2025-03-15 21:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `currency` varchar(40) DEFAULT NULL,
  `symbol` varchar(40) DEFAULT NULL,
  `method_code` int(11) DEFAULT NULL,
  `gateway_alias` varchar(40) DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `max_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `percent_charge` decimal(5,2) NOT NULL DEFAULT 0.00,
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `gateway_parameter` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(40) DEFAULT NULL,
  `cur_text` varchar(40) DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) DEFAULT NULL,
  `email_from_name` varchar(255) DEFAULT NULL,
  `email_template` text DEFAULT NULL,
  `sms_template` varchar(255) DEFAULT NULL,
  `sms_from` varchar(255) DEFAULT NULL,
  `push_title` varchar(255) DEFAULT NULL,
  `push_template` varchar(255) DEFAULT NULL,
  `pusher_config` text DEFAULT NULL,
  `base_color` varchar(40) DEFAULT NULL,
  `mail_config` text DEFAULT NULL COMMENT 'email configuration',
  `sms_config` text DEFAULT NULL,
  `firebase_config` text DEFAULT NULL,
  `global_shortcodes` text DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT 0,
  `ev` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT 1,
  `force_ssl` tinyint(1) NOT NULL DEFAULT 0,
  `in_app_payment` tinyint(1) NOT NULL DEFAULT 1,
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT 0,
  `secure_password` tinyint(1) NOT NULL DEFAULT 0,
  `agree` tinyint(1) NOT NULL DEFAULT 0,
  `multi_language` tinyint(1) NOT NULL DEFAULT 1,
  `registration` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Off	, 1: On',
  `active_template` varchar(40) DEFAULT NULL,
  `default_theme` varchar(40) DEFAULT NULL,
  `system_customized` tinyint(1) NOT NULL DEFAULT 0,
  `paginate_number` int(11) NOT NULL DEFAULT 0,
  `currency_format` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=>Both\r\n2=>Text Only\r\n3=>Symbol Only',
  `config_progress` text DEFAULT NULL,
  `socialite_credentials` text DEFAULT NULL,
  `trading_view_widget` text DEFAULT NULL,
  `deposit_commission` tinyint(1) NOT NULL DEFAULT 1,
  `trade_commission` tinyint(1) NOT NULL DEFAULT 1,
  `last_cron` text NOT NULL,
  `available_version` varchar(40) DEFAULT '2.0',
  `allow_decimal_after_number` int(11) NOT NULL DEFAULT 4,
  `metamask_login` tinyint(1) NOT NULL DEFAULT 1,
  `future_trade` tinyint(1) DEFAULT 0,
  `p2p_trade_charge` decimal(5,2) NOT NULL DEFAULT 0.00,
  `other_user_transfer_charge` decimal(5,2) NOT NULL DEFAULT 0.00,
  `wallet_types` text DEFAULT NULL,
  `cron_error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `cur_text`, `cur_sym`, `email_from`, `email_from_name`, `email_template`, `sms_template`, `sms_from`, `push_title`, `push_template`, `pusher_config`, `base_color`, `mail_config`, `sms_config`, `firebase_config`, `global_shortcodes`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `in_app_payment`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `active_template`, `default_theme`, `system_customized`, `paginate_number`, `currency_format`, `config_progress`, `socialite_credentials`, `trading_view_widget`, `deposit_commission`, `trade_commission`, `last_cron`, `available_version`, `allow_decimal_after_number`, `metamask_login`, `future_trade`, `p2p_trade_charge`, `other_user_transfer_charge`, `wallet_types`, `cron_error_message`, `created_at`, `updated_at`) VALUES
(1, 'Vinance', 'USD', '$', 'info@viserlab.com', NULL, '<table bgcolor=\"#030442\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: fixed; margin: 0px auto; color: rgb(33, 37, 41); font-family: Poppins, sans-serif; font-size: 16px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"50\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: center; vertical-align: top; font-size: 0px;\"><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: fixed; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" width=\"600\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td bgcolor=\"#0087ff\" align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: center; border-top-left-radius: 6px; border-top-right-radius: 6px; vertical-align: top; font-size: 0px;\"><table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"20\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; font-family: &quot;Open sans&quot;, Arial, sans-serif; color: rgb(255, 255, 255); font-size: 16px; font-weight: bold;\">This is a System Generated Email</td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"20\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr></tbody></table></td></tr></tbody></table><table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td bgcolor=\"#FFFFFF\" align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: center; vertical-align: top; font-size: 0px;\"><table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"35\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; vertical-align: top; font-size: 0px;\"><a href=\"https://script.viserlab.com/viserbet/admin/notification/global#\" style=\"box-sizing: border-box; color: rgb(0, 135, 255); text-decoration: none; font-size: 0.875rem;\"><img width=\"240px\" src=\"https://viserlab.com/assets/images/logoIcon/logo-dark.png\" alt=\"img\" style=\"box-sizing: border-box; vertical-align: middle; max-width: 100%; height: auto; display: block; line-height: 0px; font-size: 0px; border: 0px; width: 240px;\"></a></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"40\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 22px; color: rgb(65, 74, 81); font-weight: bold;\">Hello {{fullname}} ({{username}})</td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: center; vertical-align: top; font-size: 0px;\"><table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"20\" style=\"box-sizing: border-box; border-top-color: inherit; border-right-color: inherit; border-bottom: 3px solid rgb(0, 135, 255); border-left-color: inherit; border-top-style: solid; border-right-style: solid; border-left-style: solid; border-top-width: 0px; border-right-width: 0px; border-left-width: 0px; text-align: left;\"><br></td></tr></tbody></table></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"30\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td align=\"left\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; font-family: &quot;Open sans&quot;, Arial, sans-serif; color: rgb(127, 140, 141); font-size: 16px; line-height: 28px;\">{{message}}</td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"60\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr></tbody></table></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;\"><table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"box-sizing: border-box; caption-side: bottom; border-collapse: collapse; width: 1553px; border-spacing: 0px; table-layout: auto; margin: 0px auto;\"><tbody style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"10\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td class=\"preference-link\" align=\"center\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left; font-family: &quot;Open sans&quot;, Arial, sans-serif; color: rgb(149, 165, 166); font-size: 14px;\">© 2023<span>&nbsp;</span><a href=\"https://script.viserlab.com/viserbet/admin/notification/global#\" style=\"box-sizing: border-box; color: rgb(0, 135, 255); text-decoration: none; font-size: 0.875rem;\">{{site_name}}</a><span>&nbsp;</span>&nbsp;. All Rights Reserved.</td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"10\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px;\"><td height=\"60\" style=\"box-sizing: border-box; border-color: inherit; border-style: solid; border-width: 0px; text-align: left;\"><br></td></tr></tbody></table>', 'hi {{fullname}} ({{username}}), {{message}}', 'ViserAdmin', NULL, NULL, '{\"pusher_app_id\":\"---------------------\",\"pusher_app_key\":\"-------------------\",\"pusher_app_secret\":\"---------------------\",\"pusher_app_cluster\":\"------------------\"}', '0066FF', '{\"name\":\"php\"}', '{\"name\":\"nexmo\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}', '{\"apiKey\":\"----------------------\",\"authDomain\":\"--------------------------\",\"projectId\":\"---------------------\",\"storageBucket\":\"-------------------\",\"messagingSenderId\":\"---------------------------\",\"appId\":\"-----------------------\",\"measurementId\":\"-----------------\"}', '{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 'basic', 'light', 0, 20, 1, NULL, '{\"google\":{\"client_id\":\"---------------------\",\"client_secret\":\"---------------------\",\"status\":0},\"facebook\":{\"client_id\":\"----------------------\",\"client_secret\":\"---------------------\",\"status\":0},\"linkedin\":{\"client_id\":\"---------------------\",\"client_secret\":\"----------------------\",\"status\":0}}', '<!-- TradingView Widget BEGIN -->\r\n<div class=\"tradingview-widget-container\">\r\n  <div id=\"tradingview_92622\"></div>\r\n  <div class=\"tradingview-widget-copyright\"><a href=\"https://www.tradingview.com/\" rel=\"noopener nofollow\" target=\"_blank\"><span class=\"blue-text\">Track all markets on TradingView</span></a></div>\r\n  <script type=\"text/javascript\" src=\"https://s3.tradingview.com/tv.js\"></script>\r\n  <script type=\"text/javascript\">\r\n  new TradingView.widget(\r\n  {\r\n  \"width\": \"100%\",\r\n  \"height\": 450,\r\n  \"symbol\": \"{{pairlistingmarket}}:{{pair}}\",\r\n  \"interval\": \"D\",\r\n  \"timezone\": \"Etc/UTC\",\r\n  \"theme\": \"dark\",\r\n  \"style\": \"1\",\r\n  \"locale\": \"en\",\r\n  \"enable_publishing\": false,\r\n  \"allow_symbol_change\": true,\r\n  \"container_id\": \"tradingview_92622\"\r\n}\r\n  );\r\n  </script>\r\n</div>\r\n<!-- TradingView Widget END -->', 0, 0, '2024-10-30 07:28:03', '2.3', 4, 1, 0, 0.00, 0.00, '{\"spot\": {\"name\": \"spot\", \"title\": \"Spot Wallets\", \"type_value\": 1, \"description\": \"Users store assets available for immediate trading, configured below for various trading or transaction activities.\", \"configuration\": {\"deposit\": {\"name\": \"deposit\", \"title\": \"Deposit Fund\", \"status\": 1, \"description\": \"If you enable this module, users will deposit funds to this type of wallet.\"}, \"withdraw\": {\"name\": \"withdraw\", \"title\": \"Withdraw Fund\", \"status\": 1, \"description\": \"If you enable this module, users can withdraw their funds from this type of wallet.\"}, \"transfer_other_user\": {\"name\": \"transfer_other_user\", \"title\": \"Transfer Fund To Other Users\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other users on the platform.\"}, \"transfer_other_wallet\": {\"name\": \"transfer_other_wallet\", \"title\": \"Transfer Fund To Other Wallet\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other wallet on the platform.\"}}, \"for_fait\": 0, \"for_crypto\": 1, \"for_fiat\": 1}, \"funding\": {\"name\": \"funding\", \"title\": \"Funding Wallets\", \"type_value\": 2, \"description\": \" Reserves for deposits and funding activities, configured below for various transaction activities.\", \"configuration\": {\"deposit\": {\"name\": \"deposit\", \"title\": \"Deposit Fund\", \"status\": 1, \"description\": \"If you enable this module, users will deposit funds to this type of wallet.\"}, \"withdraw\": {\"name\": \"withdraw\", \"title\": \"Withdraw Fund\", \"status\": 1, \"description\": \"If you enable this module, users can withdraw their funds from this type of wallet.\"}, \"transfer_other_user\": {\"name\": \"transfer_other_user\", \"title\": \"Transfer Fund To Other Users\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other users on the platform.\"}, \"transfer_other_wallet\": {\"name\": \"transfer_other_wallet\", \"title\": \"Transfer Fund To Other Wallet\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other wallet on the platform.\"}}, \"for_fait\": 0, \"for_crypto\": 1, \"for_fiat\": 1}, \"future\": {\"name\": \"future\", \"title\": \"Future Wallets\", \"type_value\": 3, \"description\": \"Dedicated wallet for future trading, allowing leveraged trading on derivative contracts.\", \"configuration\": {\"deposit\": {\"name\": \"deposit\", \"title\": \"Deposit Fund\", \"status\": 1, \"description\": \"If you enable this module, users will deposit funds to this type of wallet.\"}, \"withdraw\": {\"name\": \"withdraw\", \"title\": \"Withdraw Fund\", \"status\": 1, \"description\": \"If you enable this module, users can withdraw their funds from this type of wallet.\"}, \"transfer_other_user\": {\"name\": \"transfer_other_user\", \"title\": \"Transfer Fund To Other Users\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other users on the platform.\"}, \"transfer_other_wallet\": {\"name\": \"transfer_other_wallet\", \"title\": \"Transfer Fund To Other Wallet\", \"status\": 1, \"description\": \"If you enable this module, users transfer funds from this type of wallet to other wallet on the platform.\"}}, \"for_fait\": 0, \"for_crypto\": 1, \"for_fiat\": 1}}', 'Pusher error: cURL error 7: Failed to connect to api-ap2.pusher.com port 443: Connection timed out (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://api-ap2.pusher.com/apps/1761457/events?auth_key=5aa46928371586aef3bd&auth_timestamp=1730100012&auth_version=1.0&body_md5=1de715babbf12e75e7bc8be92a514e41&auth_signature=9115efad9c29768e35751b1715c0630f7e636d323dca5db515f88508d5030007.', NULL, '2024-10-30 01:28:40');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `code` varchar(40) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: not default language, 1: default language',
  `flag` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `flag`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, '64aa848532bab1688896645.png', '2020-07-06 03:47:55', '2023-07-09 08:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `markets`
--

CREATE TABLE `markets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `currency_id` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Enable,0=Disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_data`
--

CREATE TABLE `market_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `symbol` text DEFAULT NULL,
  `pair_id` int(11) NOT NULL DEFAULT 0,
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `last_price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `market_cap` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `last_market_cap` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `percent_change_1h` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_percent_change_1h` decimal(5,2) NOT NULL DEFAULT 0.00,
  `percent_change_24h` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_percent_change_24h` decimal(5,2) NOT NULL DEFAULT 0.00,
  `percent_change_7d` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_percent_change_7d` decimal(5,2) NOT NULL DEFAULT 0.00,
  `volume_24h` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `volume_change_24h` decimal(5,2) NOT NULL DEFAULT 0.00,
  `html_classes` varchar(255) DEFAULT NULL COMMENT 'Price, percent changes html class indicator',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sender` varchar(40) DEFAULT NULL,
  `sent_from` varchar(40) DEFAULT NULL,
  `sent_to` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `notification_type` varchar(40) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `act` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `push_title` varchar(255) DEFAULT NULL,
  `email_body` text DEFAULT NULL,
  `sms_body` text DEFAULT NULL,
  `push_body` text DEFAULT NULL,
  `shortcodes` text DEFAULT NULL,
  `email_status` tinyint(1) NOT NULL DEFAULT 1,
  `email_sent_from_name` varchar(40) DEFAULT NULL,
  `email_sent_from_address` varchar(40) DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT 1,
  `sms_sent_from` varchar(40) DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', NULL, '<div><div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{wallet_currency}} has been added to your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp;&nbsp;</span></font><span style=\"text-align: var(--bs-body-text-align);\"><font face=\"Montserrat, sans-serif\"><b>{{wallet_currency}}</b></font></span><br></div><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin note:&nbsp;<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap; text-align: var(--bs-body-text-align);\">{{remark}}</span></div>', '{{amount}} {{wallet_currency}}  credited in your account. Your Current Balance {{post_balance}} {{wallet_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"', NULL, '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\",\"wallet_currency\":\"Symbol of wallet currency\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:00:01'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{wallet_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp;</span></font><font face=\"Montserrat, sans-serif\"><b>{{wallet_currency}}</b></font><div>Admin Note: {{remark}}</div>', '{{amount}} {{wallet_currency}}  debited from your account. Your Current Balance {{post_balance}} {{wallet_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}', NULL, '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:00:28'),
(3, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Completed Successfully', NULL, '<div>Your deposit of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{wallet_name}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been completed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{<span style=\"color: rgb(33, 37, 41);\">method_currency</span>}}</div><div>Charge:&nbsp;<font color=\"#000000\">{{charge}} {{</font><span style=\"color: rgb(33, 37, 41);\">method_currency</span><font color=\"#000000\">}}</font></div><div><br></div><div>Received : {{method_amount}} {{method_currency}}<br></div><div>Paid via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{wallet_name}}</span></font></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', '{{amount}} {{method_currency}} Deposit successfully by {{method_name}}', NULL, '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\",\"wallet_name\" :\"Deposited wallet name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:04:33'),
(4, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Your Deposit is Approved', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>is Approved .<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{wallet_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{</font><span style=\"color: rgb(33, 37, 41);\">wallet_name</span><font color=\"#FF0000\">}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{<span style=\"color: rgb(33, 37, 41);\">wallet_name</span>}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{wallet_name}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>', 'Admin Approve Your {{amount}} {{wallet_name}} payment request by {{method_name}} transaction : {{trx}}', NULL, '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\",\"wallet name\" :\"Deposited wallet name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:05:51'),
(5, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Your Deposit Request is Rejected', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{wallet_name}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}} has been rejected</span>.<span style=\"font-weight: bolder;\"><br></span></div><div><br></div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge: {{charge}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number was : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">if you have any queries, feel free to contact us.<br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><br><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">{{rejection_message}}</span><br>', 'Admin Rejected Your {{amount}} {{wallet_name}} payment request by {{method_name}}\r\n\r\n{{rejection_message}}', NULL, '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\",\"wallet_name\":\"Deposited Wallet Name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:07:10'),
(6, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Submitted Successfully', NULL, '<div>Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{wallet_name}}&nbsp;</span>submitted successfully<span style=\"font-weight: bolder;\">&nbsp;.<br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{wallet_name}}</div><div>Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{wallet_name}}</font></div><div><br></div><div>Payable : {{method_amount}} {{method_currency}}<br></div><div>Pay via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', '{{amount}} {{wallet_name}} Deposit requested by {{method_name}}. Charge: {{charge}} . Trx: {{trx}}', NULL, '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"wallet_name\":\"Deposited wallet name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:07:02'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">We have received a request to reset the password for your account on&nbsp;<span style=\"font-weight: bolder;\">{{time}} .<br></span></div><div style=\"font-family: Montserrat, sans-serif;\">Requested From IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>.</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><div>Your account recovery code is:&nbsp;&nbsp;&nbsp;<font size=\"6\"><span style=\"font-weight: bolder;\">{{code}}</span></font></div><div><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\" color=\"#CC0000\">If you do not wish to reset your password, please disregard this message.&nbsp;</font><br></div><div><font size=\"4\" color=\"#CC0000\"><br></font></div>', 'Your account recovery code is: {{code}}', NULL, '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2022-03-20 20:47:05'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'You have reset your password', NULL, '<p style=\"font-family: Montserrat, sans-serif;\">You have successfully reset your password.</p><p style=\"font-family: Montserrat, sans-serif;\">You changed from&nbsp; IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{time}}</span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><font color=\"#ff0000\">If you did not change that, please contact us as soon as possible.</font></span></p>', 'Your password has been changed successfully', NULL, '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2022-04-05 03:46:35'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Reply Support Ticket', NULL, '<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', NULL, '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2022-03-20 20:47:51'),
(10, 'EVER_CODE', 'Verification - Email', 'Please verify your email address', NULL, '<br><div><div style=\"font-family: Montserrat, sans-serif;\">Thanks For joining us.<br></div><div style=\"font-family: Montserrat, sans-serif;\">Please use the below code to verify your email address.<br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Your email verification code is:<font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div>', '---', NULL, '{\"code\":\"Email verification code\"}', 1, NULL, NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2022-04-03 02:32:07'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your phone verification code is: {{code}}', NULL, '{\"code\":\"SMS Verification Code\"}', 0, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2022-03-20 19:24:37'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdraw Request has been Processed and your money is sent', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{wallet_name}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Processed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{wallet_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{</font><span style=\"color: rgb(33, 37, 41);\">wallet_name</span><font color=\"#FF0000\">}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Processed Payment :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div>', 'Admin Approve Your {{amount}} {{wallet_name}} withdraw request by {{method_name}}. Transaction {{trx}}', NULL, '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\",\"wallet_name\" :\"Wallet Name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:09:28'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdraw Request has been Rejected and your money is refunded to your account', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{wallet_name}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Rejected.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{<span style=\"color: rgb(33, 37, 41);\">wallet_name</span>}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{wallet_name}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">You should get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">----</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\"><br></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\">{{amount}} {{currency}} has been&nbsp;<span style=\"font-weight: bolder;\">refunded&nbsp;</span>to your account and your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}}</span><span style=\"font-weight: bolder;\">&nbsp;{{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Rejection :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br><br><br></div><div></div><div></div>', 'Admin Rejected Your {{amount}} {{wallet_name}} withdraw request. Your Main Balance {{post_balance}}  {{method_name}} , Transaction {{trx}}', NULL, '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\",\"wallet_name\" :\" Wallet Name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:09:20'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdraw Request Submitted Successfully', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{wallet_name}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been submitted Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{wallet_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{wallet_name}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>', '{{amount}} {{wallet_name}} withdraw requested by {{method_name}}. You will get {{method_amount}} {{method_currency}} Trx: {{trx}}', NULL, '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\",\"wallet_name\":\"Waller name\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-09-19 09:09:58'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{subject}}', '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, NULL, NULL, 1, NULL, 1, '2019-09-14 13:14:22', '2024-06-02 01:19:59'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC has been approved', NULL, NULL, NULL, NULL, '[]', 1, NULL, NULL, 1, NULL, 0, NULL, NULL),
(17, 'KYC_REJECT', 'KYC Rejected Successfully', 'KYC has been rejected', NULL, NULL, NULL, NULL, '{\"reason\":\"Rejection Reason\"}', 1, NULL, NULL, 1, NULL, 0, NULL, NULL),
(18, 'ORDER_CANCEL', 'Order Cancel', 'Order Cancel Successfully', NULL, '<div>Your order cancel successfully.</div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your order:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Pair:{{pair}}</div><div style=\"font-family: Montserrat, sans-serif;\">Amount: {{amount}} {{coin symbol}}<br></div><br><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>', 'Your order cancel successfully. Pair:{{pair}} .Amount: {{amount}} {{coin symbol}}', NULL, ' {\"amount\":\"Order Amount\",\"coin symbol\":\"Order Coin Symbol\",\"pair\":\"Order Pair Symbol\",\"market_currency_symbol\" : \"Order market currency symbol\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-08-26 07:32:50'),
(19, 'ORDER_OPEN', 'Order Open', 'Order Open Successfully', NULL, '<div>Your order open successfully.</div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your order:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\">Pair:{{pair}}</div></div><div style=\"font-family: Montserrat, sans-serif;\">Amount: {{amount}}  {{coin_symbol}}</div><div style=\"font-family: Montserrat, sans-serif;\">Rate: {{rate}}</div><div style=\"font-family: Montserrat, sans-serif;\">Price {{price}}</div><div style=\"font-family: Montserrat, sans-serif;\">total: {{total}} {{market_currency_symbol}}<br></div><br><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>', 'Your order open successfully.Pair:{{pair}}Amount: {{amount}} {{coin symbol}} , Rate: {{rate}} , Price {{price}} , total: {{total}} , {{market_currency_symbol}}', NULL, ' {\"amount\":\"Order Amount\",\"coin_symbol\":\"Order Coin Symbol\",\"pair\":\"Order Pair Symbol\",\"market_currency_symbol\" : \"Order market currency symbol\",\"market\" :\"Market Name\",\"rate\":\"Coin Rate\",\"price\":\"Coin Price\",\"total\":\"Total Amount\",\"order_side\":\"Order Side\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-08-26 09:33:44'),
(20, 'ORDER_COMPLETE', 'Order Complete', 'Order Completed Successfully', NULL, '<div>Your order open successfully.</div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your order:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\">Pair:{{pair}}</div><div style=\"font-family: Montserrat, sans-serif;\">Amount: {{amount}}  {{coin_symbol}}</div></div><div style=\"font-family: Montserrat, sans-serif;\">Rate: {{rate}}</div><div style=\"font-family: Montserrat, sans-serif;\">Price {{price}}</div><div style=\"font-family: Montserrat, sans-serif;\">Filed Amount: {{filled_amount}}  {{coin_symbol}},<br></div><div style=\"font-family: Montserrat, sans-serif;\">total: {{total}} {{market_currency_symbol}}<br></div><br><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>', 'Your order open successfully.Pair:{{pair}}Amount: {{amount}} {{coin symbol}} , Rate: {{rate}} , Price {{price}} , total: {{total}} , {{market_currency_symbol}},Filed Amount: {{filled_amount}}  {{coin_symbol}},', NULL, '{\"amount\":\"Order Amount\",\"coin_symbol\":\"Order Coin Symbol\",\"pair\":\"Order Pair Symbol\",\"market_currency_symbol\" : \"Order market currency symbol\",\"market\" :\"Market Name\",\"rate\":\"Coin Rate\",\"price\":\"Coin Price\",\"total\":\"Total Amount\",\"order_side\":\"Order Side\",\"filled_amount\" : \"Filled Amount\",\"filled_percentage\" : \"Filled Amount\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2023-08-27 05:16:19'),
(21, 'REFERRAL_COMMISSION', 'Referral Commission', 'Referral Commission', NULL, '<div style=\"font-family: Montserrat, sans-serif;\">You got&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;referral commission.</div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your referral commission:</span></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">{{type}} referral commission</div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"color: rgb(33, 37, 41);\">Transaction Number : {{trx}}</span><br></div><div style=\"font-family: Montserrat, sans-serif;\">{{level}} level referral commission.</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>', 'Your got {{amount}} {{site_currency}} referral commission from {{ref_username}}.', NULL, ' {\n                \"trx\": \"Transaction number for the interest\",\n                \"amount\": \"Amount inserted by the user\",\n                \"level\": \"Which level referral commission\",\n                \"post_balance\": \"Balance of the user after this transaction\",\n                \"currency\":\"User Wallet currency symbol\"\n            }', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 00:00:00', '2023-08-27 09:22:06'),
(22, 'RECEIVED_MONEY', 'Received Money', 'Received Money Successfully', NULL, 'Received {{amount}} {{currency}} from&nbsp; {{from_username}} <br><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Received {{amount}} {{currency}} from  {{from_username}}', NULL, '{\"trx\":\"Transaction number for the transfer\",\"amount\":\"Transfer amount\",\"charge\":\"Transfer charge\",\"currency\" :\"transfer currency or wallet currency\",\"from_username\" :\"From Username\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 16:00:00', '2023-09-24 08:14:37'),
(23, 'TRANSFER_MONEY', 'Transfer Money', 'Transfer Completed Successfully', NULL, 'Sent  {{amount}} {{currency}} to {{to_username}}<br><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Sent {{amount}} {{currency}} to {{to_username}}', NULL, '{\"trx\":\"Transaction number for the transfer\",\"amount\":\"Transfer amount\",\"charge\":\"Transfer charge\",\"currency\" :\"transfer currency or wallet currency\",\"to_username\" :\"To username\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 16:00:00', '2023-09-24 08:15:50'),
(24, 'P2P_TRADE_CANCELED', 'P2P Trade Canceled', 'Canceled On Your Trade', NULL, '<div>Canceled On Your Trade. Below are the trade Details</div><div><br></div><div>Order ID: {{order_id}},</div><div>Asset Amount: {{asset_amount}},</div><div>Fiat Amount: {{fiat_amount}},</div><div>Date: {{date}}<br></div>', 'Canceled On Your Trade. Below are the trade Details', NULL, '  {\"order_id\":\"Order ID of the trade\",\"asset_amount\":\"Buy/Sell asset amount\",\"fiat_amount\":\"Trade with this fiat amount\",\"asset\" :\"crypto currency symbol\",\"fiat\" :\"Fiat Currency Symbol\",\"date\":\"Trade Date Time\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 20:00:00', '2023-12-11 11:25:57'),
(25, 'P2P_TRADE_PAID', 'P2P Trade Paid', 'Paid On Your Trade', NULL, '<div>Paid On Your Trade. Below are the trade Details</div><div><br></div><div>Order ID: {{order_id}},</div><div>Asset Amount: {{asset_amount}},</div><div>Fiat Amount: {{fiat_amount}},</div><div>Date: {{date}}<br></div>', 'Release On Your Trade. Below are the trade Details', NULL, '  {\"order_id\":\"Order ID of the trade\",\"asset_amount\":\"Buy/Sell asset amount\",\"fiat_amount\":\"Trade with this fiat amount\",\"asset\" :\"crypto currency symbol\",\"fiat\" :\"Fiat Currency Symbol\",\"date\":\"Trade Date Time\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 20:00:00', '2023-12-11 11:14:47'),
(26, 'P2P_TRADE_RELEASE', 'P2P Trade Release', 'Release On Your Trade', NULL, '<div>Release On Your Trade. Below are the trade Details</div><div><br></div><div>Order ID: {{order_id}},</div><div>Asset Amount: {{asset_amount}},</div><div>Fiat Amount: {{fiat_amount}},</div><div>Date: {{date}}<br></div>', 'Release On Your Trade. Below are the trade Details', NULL, '  {\"order_id\":\"Order ID of the trade\",\"asset_amount\":\"Buy/Sell asset amount\",\"fiat_amount\":\"Trade with this fiat amount\",\"asset\" :\"crypto currency symbol\",\"fiat\" :\"Fiat Currency Symbol\",\"date\":\"Trade Date Time\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 20:00:00', '2023-12-11 11:11:19'),
(27, 'P2P_TRADE_REPORT', 'P2P Trade Report', 'Report On Your Trade', NULL, '<div>Report On Your Trade. Below are the trade Details</div><div><br></div><div>Order ID: {{order_id}},</div><div>Asset Amount: {{asset_amount}},</div><div>Fiat Amount: {{fiat_amount}},</div><div>Date: {{date}}<br></div>', 'Report On Your Trade. Below are the trade Details', NULL, '{\"order_id\":\"Order ID of the trade\",\"asset_amount\":\"Buy/Sell asset amount\",\"fiat_amount\":\"Trade with this fiat amount\",\"asset\" :\"crypto currency symbol\",\"fiat\" :\"Fiat Currency Symbol\",\"date\":\"Trade Date Time\",\"report_date\":\"Reported Date Time\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 20:00:00', '2023-12-11 11:08:01'),
(28, 'P2P_TRADE', 'P2P Trade', 'New Trade on Your Ad', NULL, '<div>New Trade on Your Ad. Below is trade Details</div><div><br></div><div>Order ID: {{order_id}},</div><div>Asset Amount: {{asset_amount}},</div><div>Fiat Amount: {{fiat_amount}},</div><div>Date: {{date}}<br></div>', 'New Trade on Your Ad. Below is trade Details.Order ID: {{order_id}},Asset Amount: {{asset_amount}},\r\nFiat Amount: {{fiat_amount}},Date: {{date}}', NULL, '{\"order_id\":\"Order ID of the trade\",\"asset_amount\":\"Buy/Sell asset amount\",\"fiat_amount\":\"Trade with this fiat amount\",\"asset\" :\"crypto currency symbol\",\"fiat\" :\"Fiat Currency Symbol\",\"date\":\"Trade Date Time\"}', 1, NULL, NULL, 1, NULL, 0, '2021-11-03 20:00:00', '2023-12-11 10:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pair_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `coin_id` tinyint(1) NOT NULL DEFAULT 0,
  `market_currency_id` tinyint(1) NOT NULL DEFAULT 0,
  `trx` text DEFAULT NULL,
  `order_side` tinyint(1) NOT NULL COMMENT '1=buy,2=sell',
  `order_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Limit Order, 2=Market Order,3=Stop Limit order',
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'user providing rate',
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'coin price',
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'coin quantity',
  `total` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `filled_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `filed_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `stop_rate` decimal(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'stop rate for the stop limit ordder',
  `is_draft` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=no,1=yes',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Open,1=Completed,2=pending,9=canceled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_ads`
--

CREATE TABLE `p2p_ads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=buy,2=sell',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `fiat_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_window_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `price_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Price type fixed,2=price type margin',
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `price_margin` decimal(5,2) NOT NULL DEFAULT 0.00,
  `minimum_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `maximum_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `payment_details` longtext DEFAULT NULL,
  `terms_of_trade` longtext DEFAULT NULL,
  `auto_replay_text` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=disbale,1=enable',
  `complete_step` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_ad_payment_methods`
--

CREATE TABLE `p2p_ad_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ad_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_method_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_payment_methods`
--

CREATE TABLE `p2p_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `supported_currency` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active,0=Inactive',
  `form_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `branding_color` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_payment_windows`
--

CREATE TABLE `p2p_payment_windows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `minute` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_trades`
--

CREATE TABLE `p2p_trades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uid` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=buy,2=sell',
  `ad_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `buyer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `seller_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_method_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_window_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `asset_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `fiat_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=pending,1=completed,2=please relase,3=Reported,9=cancel',
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_trade_feed_backs`
--

CREATE TABLE `p2p_trade_feed_backs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `provide_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Positive,0=Negative',
  `trade_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_trade_messages`
--

CREATE TABLE `p2p_trade_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trade_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `sender_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `receiver_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `message` longtext DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p2p_user_payment_methods`
--

CREATE TABLE `p2p_user_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payment_method_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `slug` varchar(40) DEFAULT NULL,
  `tempname` varchar(40) DEFAULT NULL COMMENT 'template name',
  `secs` text DEFAULT NULL,
  `seo_content` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `seo_content`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'HOME', '/', 'templates.basic.', '[\"coin_pair\",\"choose_us\",\"how_to_invest\",\"crypto_currency\",\"product\",\"faq\",\"subscribe\"]', NULL, 1, '2020-07-11 06:23:58', '2023-08-24 04:09:55'),
(5, 'Contact', 'contact', 'templates.basic.', NULL, NULL, 1, '2020-10-22 01:14:53', '2020-10-22 01:14:53'),
(19, 'Market', 'market', 'templates.basic.', NULL, NULL, 1, '2023-09-05 11:05:34', '2023-09-05 11:31:23'),
(20, 'Crypto Currency', 'crypto-currency', 'templates.basic.', NULL, NULL, 1, '2023-09-05 11:05:57', '2023-09-05 11:05:57'),
(23, 'About', 'about-us', 'templates.basic.', '[\"product\",\"crypto_currency\",\"how_to_invest\",\"choose_us\",\"subscribe\"]', NULL, 0, '2023-09-25 05:01:03', '2023-09-25 05:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) NOT NULL,
  `commission_type` varchar(40) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `percent` decimal(5,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_message_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `message` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `ticket` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE `trades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `pair_id` int(11) NOT NULL DEFAULT 0,
  `trader_id` int(11) NOT NULL DEFAULT 0,
  `trade_side` tinyint(1) NOT NULL COMMENT '1=buy,2=sell',
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `total` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `post_balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `trx_type` varchar(40) DEFAULT NULL,
  `trx` varchar(40) DEFAULT NULL,
  `details` varchar(255) DEFAULT NULL,
  `remark` varchar(40) DEFAULT NULL,
  `wallet_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_logs`
--

CREATE TABLE `update_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(40) DEFAULT NULL,
  `update_log` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `lastname` varchar(40) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(40) NOT NULL,
  `dial_code` varchar(40) DEFAULT NULL,
  `country_code` varchar(40) DEFAULT NULL,
  `mobile` varchar(40) DEFAULT NULL,
  `ref_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `password` varchar(255) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL COMMENT 'contains full address',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: banned, 1: active',
  `kyc_data` text DEFAULT NULL,
  `kyc_rejection_reason` varchar(255) DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT 0,
  `ver_code` varchar(40) DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) DEFAULT NULL,
  `ban_reason` varchar(255) DEFAULT NULL,
  `provider` varchar(40) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `metamask_wallet_address` text DEFAULT NULL,
  `metamask_nonce` text DEFAULT NULL,
  `provider_id` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_ip` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `country_code` varchar(40) DEFAULT NULL,
  `longitude` varchar(40) DEFAULT NULL,
  `latitude` varchar(40) DEFAULT NULL,
  `browser` varchar(40) DEFAULT NULL,
  `os` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `currency_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `wallet_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1=SPOT,2=FUNDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `method_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `currency` varchar(40) DEFAULT NULL,
  `wallet_id` int(11) NOT NULL DEFAULT 0,
  `rate` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `trx` varchar(40) DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `after_charge` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `withdraw_information` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(40) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT 0.00000000,
  `max_limit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `fixed_charge` decimal(28,8) DEFAULT 0.00000000,
  `rate` decimal(28,8) DEFAULT 0.00000000,
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `binary_trades`
--
ALTER TABLE `binary_trades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coin_pairs`
--
ALTER TABLE `coin_pairs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_name_unique` (`name`),
  ADD UNIQUE KEY `currencies_symbol_unique` (`symbol`);

--
-- Indexes for table `currency_data_providers`
--
ALTER TABLE `currency_data_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_pairs`
--
ALTER TABLE `favorite_pairs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `future_orders`
--
ALTER TABLE `future_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `future_queue_orders`
--
ALTER TABLE `future_queue_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `future_trades`
--
ALTER TABLE `future_trades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `future_trade_configs`
--
ALTER TABLE `future_trade_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `code_2` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `markets`
--
ALTER TABLE `markets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `markets_name_unique` (`name`);

--
-- Indexes for table `market_data`
--
ALTER TABLE `market_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_ads`
--
ALTER TABLE `p2p_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_ad_payment_methods`
--
ALTER TABLE `p2p_ad_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_payment_methods`
--
ALTER TABLE `p2p_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `p2_p_payment_methods_name_unique` (`name`),
  ADD UNIQUE KEY `p2_p_payment_methods_slug_unique` (`slug`);

--
-- Indexes for table `p2p_payment_windows`
--
ALTER TABLE `p2p_payment_windows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `p2_p_payment_windows_name_unique` (`minute`);

--
-- Indexes for table `p2p_trades`
--
ALTER TABLE `p2p_trades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_trade_feed_backs`
--
ALTER TABLE `p2p_trade_feed_backs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_trade_messages`
--
ALTER TABLE `p2p_trade_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p2p_user_payment_methods`
--
ALTER TABLE `p2p_user_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_logs`
--
ALTER TABLE `update_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `binary_trades`
--
ALTER TABLE `binary_trades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coin_pairs`
--
ALTER TABLE `coin_pairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency_data_providers`
--
ALTER TABLE `currency_data_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favorite_pairs`
--
ALTER TABLE `favorite_pairs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `future_orders`
--
ALTER TABLE `future_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `future_queue_orders`
--
ALTER TABLE `future_queue_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `future_trades`
--
ALTER TABLE `future_trades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `future_trade_configs`
--
ALTER TABLE `future_trade_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `markets`
--
ALTER TABLE `markets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_data`
--
ALTER TABLE `market_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_ads`
--
ALTER TABLE `p2p_ads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_ad_payment_methods`
--
ALTER TABLE `p2p_ad_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_payment_methods`
--
ALTER TABLE `p2p_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_payment_windows`
--
ALTER TABLE `p2p_payment_windows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_trades`
--
ALTER TABLE `p2p_trades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_trade_feed_backs`
--
ALTER TABLE `p2p_trade_feed_backs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_trade_messages`
--
ALTER TABLE `p2p_trade_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `p2p_user_payment_methods`
--
ALTER TABLE `p2p_user_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trades`
--
ALTER TABLE `trades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `update_logs`
--
ALTER TABLE `update_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
