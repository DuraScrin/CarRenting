CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (car_id) REFERENCES cars(id)
);

CREATE TABLE analytics_events (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(50) NOT NULL,
    page_path VARCHAR(255) NOT NULL,
    target_type VARCHAR(50) DEFAULT NULL,
    target_identifier VARCHAR(100) DEFAULT NULL,
    car_id INT DEFAULT NULL,
    session_hash CHAR(64) NOT NULL,
    client_fingerprint_hash CHAR(64) NOT NULL,
    visitor_key CHAR(64) NOT NULL,
    client_ip VARCHAR(45) DEFAULT NULL,
    metadata_json TEXT DEFAULT NULL,
    occurred_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (car_id) REFERENCES cars(id),
    INDEX idx_analytics_occurred_at (occurred_at),
    INDEX idx_analytics_page_path_occurred_at (page_path, occurred_at),
    INDEX idx_analytics_event_name_occurred_at (event_name, occurred_at),
    INDEX idx_analytics_target_occurred_at (target_type, target_identifier, occurred_at),
    INDEX idx_analytics_session_hash_occurred_at (session_hash, occurred_at),
    INDEX idx_analytics_visitor_key_occurred_at (visitor_key, occurred_at),
    INDEX idx_analytics_client_ip_occurred_at (client_ip, occurred_at),
    INDEX idx_analytics_car_id_occurred_at (car_id, occurred_at)
);

CREATE VIEW analytics_daily_visitors AS
SELECT
    DATE(occurred_at) AS visit_date,
    COUNT(*) AS total_page_views,
    COUNT(DISTINCT visitor_key) AS unique_visitors
FROM analytics_events
WHERE event_name = 'page_view'
GROUP BY DATE(occurred_at);

CREATE VIEW analytics_page_popularity AS
SELECT
    page_path,
    COUNT(*) AS views_count,
    COUNT(DISTINCT visitor_key) AS unique_visitors
FROM analytics_events
WHERE event_name = 'page_view'
GROUP BY page_path;

CREATE VIEW analytics_button_clicks AS
SELECT
    page_path,
    COALESCE(target_type, 'unknown') AS target_type,
    COALESCE(target_identifier, 'unknown') AS target_identifier,
    COUNT(*) AS clicks_count,
    COUNT(DISTINCT visitor_key) AS unique_clickers
FROM analytics_events
WHERE event_name = 'button_click'
GROUP BY
    page_path,
    COALESCE(target_type, 'unknown'),
    COALESCE(target_identifier, 'unknown');

CREATE VIEW analytics_popular_cars AS
SELECT
    COALESCE(c.id, ae.car_id) AS car_id,
    COALESCE(CONCAT(c.make, ' ', c.model), ae.target_identifier) AS car_label,
    SUM(CASE WHEN ae.event_name = 'car_view' THEN 1 ELSE 0 END) AS car_views,
    SUM(CASE WHEN ae.event_name = 'car_book_click' THEN 1 ELSE 0 END) AS booking_clicks,
    COUNT(*) AS popularity_score
FROM analytics_events ae
LEFT JOIN cars c ON c.id = ae.car_id
WHERE ae.event_name IN ('car_view', 'car_book_click')
GROUP BY
    COALESCE(c.id, ae.car_id),
    COALESCE(CONCAT(c.make, ' ', c.model), ae.target_identifier);

CREATE VIEW analytics_events_per_user AS
SELECT
    visitor_key,
    COALESCE(client_ip, 'unknown') AS client_ip,
    event_name,
    page_path,
    COUNT(*) AS events_count,
    MIN(occurred_at) AS first_seen_at,
    MAX(occurred_at) AS last_seen_at
FROM analytics_events
GROUP BY visitor_key, COALESCE(client_ip, 'unknown'), event_name, page_path;

CREATE VIEW analytics_user_summary AS
SELECT
    visitor_key,
    COALESCE(client_ip, 'unknown') AS client_ip,
    COUNT(*) AS total_events,
    COUNT(DISTINCT page_path) AS unique_pages,
    MIN(occurred_at) AS first_seen_at,
    MAX(occurred_at) AS last_seen_at
FROM analytics_events
GROUP BY visitor_key, COALESCE(client_ip, 'unknown');