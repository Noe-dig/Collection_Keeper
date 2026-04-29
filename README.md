# 📀 Collection Keeper

A lightweight PHP application to manage your personal collection of Vinyls, CDs, and Cassettes. It uses the **MusicBrainz API** and **Cover Art Archive** to automatically fetch and store high-quality album artwork.



## 🚀 Features
* **Automatic Cover Art**: Just enter the artist and album name; the app fetches the art for you.
* **User Authentication**: Secure signup and login using `password_hash` and `password_verify`.
* **Personal Collections**: Each user sees only their own items.
* **Guest Access**: Allows users to browse a public collection without logging in.
* **CRUD Functionality**: Easily add or delete items from your collection.
* **Performance Optimized**: Image URLs are cached in the database to prevent API rate-limiting and slow load times.

---

## 🛠️ Installation & Setup

### 1. Database Configuration
Create a database named `CollectionKeeper` and run the following SQL script in your MySQL terminal or phpMyAdmin:

```sql
DROP DATABASE IF EXISTS `CollectionKeeper`;
CREATE DATABASE `CollectionKeeper`;
USE `CollectionKeeper`;

CREATE TABLE `users` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE `media` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    artist VARCHAR(255) NOT NULL,
    album VARCHAR(255) NOT NULL,
    type ENUM('vinyl', 'cd', 'cassette') NOT NULL,
    cover_url VARCHAR(255) DEFAULT 'img.png',
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 2. Connection Setup
Edit `connection.php` and update it with your local server credentials:

```php
$host = 'localhost';
$db   = 'CollectionKeeper';
$user = 'root'; // Your MySQL username
$pass = '';     // Your MySQL password
```

### 3. API User-Agent
Open `api.php` and update the `User-Agent` header with your email address to comply with MusicBrainz's API guidelines:
```php
"header" => "User-Agent: CollectionKeeper/1.0 ( your-email@example.com )"
```

---

## 📂 Project Structure
* `index.php`: The main dashboard where collections are displayed and items are added.
* `api.php`: Handles the logic for searching MusicBrainz and the Cover Art Archive.
* `login.php` / `signup.php`: Handles user sessions and secure account creation.
* `logout.php`: Clears the user session.
* `connection.php`: Database connection logic using PDO.
* `style.css`: All layout and design styling.

---

## 📖 How to Use
1.  **Sign Up**: Create an account to start your private collection.
2.  **Add Media**: Select the format (Vinyl, CD, or Cassette), type the Artist and Album name, and click "Add."
3.  **Automatic Fetch**: The system will ping MusicBrainz for the ID and grab the official cover from the Archive.
4.  **Manage**: Use the red `x` button to remove items from your shelf.

---

## 🛡️ Security Notes
* Passwords are never stored in plain text; they are encrypted using **Bcrypt**.
* All database interactions use **PDO Prepared Statements** to prevent SQL Injection.
* Sessions are used to manage user states securely across pages.

## 📄 License
This project is open-source and free to use for personal hobbyist collections.

## API
This project makes use of the https://coverartarchive.org API