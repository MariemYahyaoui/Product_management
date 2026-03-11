# 🛒 Symfony Product Management System

A professional web application built with **Symfony ** for managing a product catalog. This project features a secure administrative dashboard, automated image handling and all the CRUD functionnalities.

---

## 🚀 Key Features

* **Product CRUD:** Full management (Create, Read, Update, Delete) of product entities.
* **Secure Administration:** Sensitive actions are restricted to `ROLE_ADMIN` using Symfony's `IsGranted` attributes.
* **Smart Image Uploads:** * Automated file naming using `SluggerInterface`.
* Automatic cleanup: Deletes old images from the server when a product is updated or deleted.
* **Dynamic Search:** Integrated AJAX search functionality to filter products by name or category.
* **Modern UI:** Utilizes Twig, Bootstrap, and Modal forms for a seamless user experience.

---

## 📸 Screenshots

### 1. Product Dashboard
![Main Index]
<img width="1658" height="871" alt="image" src="https://github.com/user-attachments/assets/7fffb1b5-85b9-4861-9b1d-62cb8429290a" />

*The main view accessible to all users.*

### 2. Admin - Product Management
![Admin Modal]
<img width="1661" height="875" alt="image" src="https://github.com/user-attachments/assets/1f0a1f97-b01f-47c0-a649-d5551d9a2259" />

The Login Page :
<img width="1637" height="800" alt="image" src="https://github.com/user-attachments/assets/934cbfdf-fcc8-40f8-ab0a-36c6da8294ab" />
Add New product or Category:
<img width="1206" height="840" alt="image" src="https://github.com/user-attachments/assets/b6176c04-97f7-451e-9552-8a6a74935efa" /> 
<img width="936" height="693" alt="image" src="https://github.com/user-attachments/assets/f519b0e0-0455-4983-a62a-c3b012fe5b78" />
Edit The Pducts and Category:
<img width="930" height="626" alt="image" src="https://github.com/user-attachments/assets/88703619-e01c-4416-ac85-b19c53106a6d" />

<img width="928" height="820" alt="image" src="https://github.com/user-attachments/assets/90855a45-b42e-4956-a9ff-ce2ecc748000" />

*Admin-only modal for adding/editing products with image preview.*

### 3. Client - Buyer
![Client Modal]
<img width="1657" height="868" alt="image" src="https://github.com/user-attachments/assets/07c4a0fb-9afc-4e5d-a3e9-972c81284da8" />

Adding to the cart :
<img width="1649" height="600" alt="image" src="https://github.com/user-attachments/assets/95f160de-c948-46cb-9bf4-25be300317ff" />

Modify or Proceed to Purchase :
<img width="1655" height="871" alt="image" src="https://github.com/user-attachments/assets/4a14a730-4b13-4202-b2cf-0bdb1555bff4" />

Confirmation Message with credit card details :
<img width="956" height="758" alt="image" src="https://github.com/user-attachments/assets/bf976877-a9db-417b-a2ff-7e298c6b5a25" />
Error handling :
<img width="730" height="780" alt="image" src="https://github.com/user-attachments/assets/c199d9f5-4a65-49d1-90b5-48e79f76bea0" />
After completing your order, a printable receipt will be available:
<img width="750" height="652" alt="image" src="https://github.com/user-attachments/assets/84b41862-b6df-4999-b3e4-1ca0c1182df9" />


## 🔐 Security Configuration

The application uses a **Role-Based Access Control (RBAC)** system:

| Action | Required Role | Method |
| :--- | :--- | :--- |
| View Catalog | Public (IS_AUTHENTICATED_ANONYMOUSLY) | `GET` |
| Search Products | Public | `GET` |
| Create Product | `ROLE_ADMIN` | `POST` |
| Edit Product | `ROLE_ADMIN` | `POST` |
| Delete Product | `ROLE_ADMIN` | `POST` (CSRF Protected) |



---

## 🛠️ Installation & Setup

### Prerequisites
* **PHP 8.2+**
* **Composer**
* **MySQL / MariaDB**
* **Symfony CLI**

### Setup Steps
1. **Clone the project:**
   ```bash
   git clone [[https://github.com/.......](https://github.com/MariemYahyaoui/Product_management.git))
   cd product_management
