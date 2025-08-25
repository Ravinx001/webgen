# SLUDI OAuth PHP Authentication Server

A simple yet powerful PHP **JWT-based authentication server** compatible with SLUDI digital identity systems.

## 🔑 Features

- **Username/Password Login** — Secure user authentication.
- **JWT Access Token & ID Token Generation** — Uses RS256 algorithm for robust security.
- **Public JWKS Endpoint** — Token verification made easy for external clients.
- **Login Activity Logging** — Track and audit authentication attempts.
- **Environment-Based Configuration** — Easily manage settings via `.env` files.

## 🚀 Quick Setup Guide

### 1. Copy the Environment Example

cp .env.example .env

### 2. Setup the Database

- Import SQL located at:  
  `/src/DB/sludi.sql`  
  Use your preferred MySQL tool or CLI to import.

### 3. Install Dependencies

composer install


### 4. Start the Server

php -S localhost:8000


## 🔐 How to Authenticate (Login)

Send a POST request to:

http://localhost:8000/authenticate.php


**With JSON body:**

{
"username": "nimal",
"password": "password123"
}


**Example Response:**

{
"status": "SUCCESS",
"access_token": "...",
"id_token": "...",
"token_type": "Bearer",
"expires_in": 3600
}


## 🗝️ JWT Verification - Public JWKS Endpoint

Retrieve public keys for verifying JWTs at:

http://localhost:8000/jwks.php


> **Tip:**  
> All environment-specific settings are managed in the `.env` file.  
> Ensure you configure your database and JWT secrets appropriately before running in production.

---

Feel free to customize this README further as needed!

