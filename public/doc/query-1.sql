ALTER TABLE <table>
ADD COLUMN `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD COLUMN `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL

-- || --------------------------------------------------------------------- 
