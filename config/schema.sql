CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_hash VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    login_attempts INT UNSIGNED DEFAULT 0,    
    last_login_attempt DATETIME DEFAULT NULL,    
    is_active TINYINT DEFAULT 0,
    user_role ENUM('individual', 'agent') NOT NULL DEFAULT 'individual',   
    authentication_code INT DEFAULT NULL,   
    authentication_code_expiry DATETIME DEFAULT NULL,
    authentication_code_attempts INT UNSIGNED DEFAULT 0,    
    email_send_count INT UNSIGNED DEFAULT NULL,  
    last_email_sent_at DATETIME DEFAULT NULL,    
    new_email VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email_hash ON users (email_hash);

CREATE TABLE individuals (
    user_id INT PRIMARY KEY,
    nino VARCHAR(255) NOT NULL UNIQUE,
    nino_hash VARCHAR(64) NOT NULL UNIQUE,
    access_token VARCHAR(255),
    refresh_token VARCHAR(255),
    token_expiry DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE    
);

CREATE INDEX idx_individual_nino_hash ON individuals (nino_hash);

CREATE TABLE agent_firms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    arn VARCHAR(255) NOT NULL UNIQUE,
    arn_hash VARCHAR(64) NOT NULL UNIQUE    
);

CREATE TABLE agents (
    user_id INT PRIMARY KEY,
    agent_firm_id INT NOT NULL,
    access_token VARCHAR(255),
    refresh_token VARCHAR(255),
    token_expiry DATETIME,
    agent_admin TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_firm_id) REFERENCES agent_firms(id) ON DELETE CASCADE    
);

CREATE INDEX idx_agents_firm_id ON agents (agent_firm_id);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nino VARCHAR(255) NOT NULL UNIQUE,
    nino_hash VARCHAR(64) NOT NULL UNIQUE   
);

CREATE INDEX idx_clients_nino_hash ON clients (nino_hash);

CREATE TABLE clients_agents (
    client_id INT NOT NULL,
    agent_firm_id INT NOT NULL,
    client_name VARCHAR(255),   
    authorisation ENUM('main', 'supporting') DEFAULT NULL, 
    PRIMARY KEY (client_id, agent_firm_id),
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_firm_id) REFERENCES agent_firms(id) ON DELETE CASCADE
);

CREATE INDEX idx_clients_agents_client_id ON clients_agents (client_id);
CREATE INDEX idx_clients_agents_agent_firm_id ON clients_agents (agent_firm_id);

CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nino VARCHAR(255) NOT NULL,
    nino_hash VARCHAR(64) NOT NULL,
    
    submitted_by_user_id INT DEFAULT NULL,
    submitted_by_firm_id INT DEFAULT NULL,
    submitted_by_type ENUM('individual', 'agent') NOT NULL,
    
    business_id VARCHAR(255) DEFAULT NULL,
    business_id_hash VARCHAR(64) DEFAULT NULL,
    period_start DATE,
    period_end DATE,
    tax_year VARCHAR(7) NOT NULL,   
    submitted_at DATETIME NOT NULL,    
    submission_type VARCHAR(20) NOT NULL,
    submission_reference VARCHAR(100) NOT NULL,
    submission_payload JSON,
    deleted_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,   
    FOREIGN KEY (submitted_by_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (submitted_by_firm_id) REFERENCES agent_firms(id) ON DELETE CASCADE,
    INDEX idx_nino_hash (nino_hash),
    INDEX idx_business_id_hash (business_id_hash),
    INDEX idx_tax_year (tax_year),
    INDEX idx_submission_type (submission_type),
    INDEX idx_submission_reference (submission_reference),
    INDEX idx_submitted_by_user_id (submitted_by_user_id),
    INDEX idx_submitted_by_firm_id (submitted_by_firm_id)
);


CREATE TABLE user_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    device_id VARCHAR(255) NOT NULL,    
    last_verified_at DATETIME NOT NULL,
    unique_mfa_ref VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE (user_id, device_id),
    INDEX idx_device_id (device_id),
    INDEX idx_user_id (user_id)
);


-- probably take this out
CREATE TABLE bot_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT DEFAULT NULL,
    request_uri TEXT DEFAULT NULL,   
    occurred_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);





