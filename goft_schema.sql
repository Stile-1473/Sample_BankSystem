-- Creator: ghost1473
-- SQL schema for Goft Bank (updated)
-- Creates full schema with required columns and constraints.
-- Import this into MySQL to initialize the database without runtime schema changes.

CREATE DATABASE IF NOT EXISTS goft;
USE goft;

-- Disable FK checks during DROP phase to avoid dependency errors
SET FOREIGN_KEY_CHECKS = 0;

-- Drop in child-to-parent order
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS failed_logins;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS users;

-- Re-enable FK checks before creating tables
SET FOREIGN_KEY_CHECKS = 1;

-- Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NULL,
    phone VARCHAR(20) NULL,
    role ENUM('admin','manager','cashier','customer') NOT NULL DEFAULT 'customer',
    status ENUM('active','blocked','deleted') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Accounts
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('active','pending','closed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_accounts_user FOREIGN KEY (user_id) REFERENCES users(id),
    KEY idx_accounts_user (user_id),
    KEY idx_accounts_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    to_account_id INT NULL,
    pair_id INT NULL,
    type ENUM('deposit','withdrawal','transfer') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending','completed','failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_transactions_account FOREIGN KEY (account_id) REFERENCES accounts(id),
    CONSTRAINT fk_transactions_to_account FOREIGN KEY (to_account_id) REFERENCES accounts(id),
    CONSTRAINT fk_transactions_pair FOREIGN KEY (pair_id) REFERENCES transactions(id),
    KEY idx_tx_account (account_id),
    KEY idx_tx_to_account (to_account_id),
    KEY idx_tx_status (status),
    KEY idx_tx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Audit Logs
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id),
    KEY idx_audit_user (user_id),
    KEY idx_audit_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Failed logins (rate limiting)
CREATE TABLE failed_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    ip_address VARCHAR(45),
    attempts INT DEFAULT 1,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_failed_user_ip (username, ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notes:
-- 1) This script DROPs and recreates tables for a clean install. Do not run in production without backups.
-- 2) For existing databases, prefer ALTER statements instead of DROP/CREATE, e.g.:
--    ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL AFTER password;
--    ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER email;
--    ALTER TABLE users ADD COLUMN status ENUM('active','blocked','deleted') NOT NULL DEFAULT 'active' AFTER role;
--    ALTER TABLE transactions ADD COLUMN to_account_id INT NULL AFTER account_id;
--    ALTER TABLE transactions ADD COLUMN pair_id INT NULL AFTER to_account_id;
--    ALTER TABLE transactions ADD CONSTRAINT fk_transactions_to_account FOREIGN KEY (to_account_id) REFERENCES accounts(id);
--    ALTER TABLE transactions ADD CONSTRAINT fk_transactions_pair FOREIGN KEY (pair_id) REFERENCES transactions(id);
--    CREATE INDEX idx_tx_status ON transactions(status);
--    CREATE INDEX idx_tx_type ON transactions(type);
