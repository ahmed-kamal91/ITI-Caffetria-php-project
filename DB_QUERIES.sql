-- Create Database
CREATE DATABASE PHP_Project;
USE PHP_Project;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    image_path VARCHAR(255),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE
);

-- Drinks Table
CREATE TABLE drinks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2),
    image_path VARCHAR(255),
    available BOOLEAN DEFAULT TRUE,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    notes TEXT,
    total DECIMAL(10, 2),
    status ENUM('Processing', 'out for delivery', 'completed', 'cancelled') DEFAULT 'Processing',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order_Drinks Table
CREATE TABLE order_drinks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    drink_id INT,
    quantity INT DEFAULT 1,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (drink_id) REFERENCES drinks(id) ON DELETE CASCADE
);

-- ------------------------------------------
-- INSERT SAMPLE DATA
-- ------------------------------------------

-- Insert Categories
INSERT INTO categories (name) VALUES
('Hot'),
('Cold');

-- Insert Drinks
INSERT INTO drinks (name, price, image_path, available, category_id) VALUES
('Apricot Juice', 1.80, 'uploads/drinks/apricotJuice.jpg', TRUE, 2),
('Cola', 1.50, 'uploads/drinks/cola.jpg', TRUE, 2),
('Red Cola', 1.60, 'uploads/drinks/redCola.jpg', TRUE, 2),
('Blue Cocktail', 2.20, 'uploads/drinks/blueCoctail.jpg', TRUE, 2),
('Moccha', 2.50, 'uploads/drinks/moccha.png', TRUE, 1),
('Takeaway Caffe', 2.00, 'uploads/drinks/takeaway caffe.jpg', TRUE, 1),
('Coffee with Chocolate', 2.40, 'uploads/drinks/coffeWithChocolate.jpg', TRUE, 1),
('Moccha with Popcorn', 2.60, 'uploads/drinks/mocchaWithBobCorn.jpg', TRUE, 1),
('Coffee with Cinnamon Stick', 2.30, 'uploads/drinks/coffeWithCinamonStick.jpg', TRUE, 1),
('Orange Juice', 1.90, 'uploads/drinks/orangeJuice.jpg', TRUE, 2);

-- View All Drinks
SELECT * FROM drinks;

