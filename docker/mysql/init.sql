-- Initialize database with proper character set
ALTER DATABASE meracikopi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant all privileges to the application user
GRANT ALL PRIVILEGES ON meracikopi.* TO 'meracikopi'@'%';
FLUSH PRIVILEGES;
