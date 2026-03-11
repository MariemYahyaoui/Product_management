# 🛒 Symfony Product Management & E-Commerce System

A comprehensive web application built with **Symfony 7** designed for seamless product management and a streamlined shopping experience. This project features a robust administrative dashboard, automated image handling, secure checkout, and a real-time search engine.

---

## 🚀 Key Features

* **Full CRUD Suite:** Complete lifecycle management for Products and Categories.
* **Secure Administration:** Sensitive operations (Create/Edit/Delete) are strictly protected via `ROLE_ADMIN` using Symfony's `IsGranted` attributes.
* **Advanced Image Processing:** * Automated file naming and normalization using `SluggerInterface`.
    * **Smart Cleanup:** Automatically deletes orphaned image files from the server when products are updated or removed.
* **E-Commerce Workflow:** * Functional shopping cart for clients.
    * Secure checkout process with credit card validation and error handling.
    * **Printable Receipts:** Generate a physical record immediately after order completion.
* **Dynamic Search:** AJAX-powered search functionality to filter the catalog by name or category instantly.
* **Modern UI/UX:** Built with Twig, Bootstrap, and interactive Modal forms.

---

## 📸 Project Walkthrough

### 🔐 Authentication & Security
*Secure access for both Clients and Administrators.*

| Sign In | Sign Up |
| :--- | :--- |
| <img width="400" alt="Sign In" src="https://github.com/user-attachments/assets/201a634e-ddcc-4308-a9e6-5842c5ec1b2c" /> | <img width="400" alt="Sign Up" src="https://github.com/user-attachments/assets/0d72e86f-eec6-40b8-98d0-b132797c142f" /> |

---

### 👤 Client Experience (Buyer Flow)
*A smooth journey from product discovery to purchase.*

**1. Product Catalog & Search**
![Main Index](https://github.com/user-attachments/assets/7fffb1b5-85b9-4861-9b1d-62cb8429290a)

**2. Shopping Cart & Checkout**
| Adding to Cart | Order Summary |
| :--- | :--- |
| <img width="400" src="https://github.com/user-attachments/assets/95f160de-c948-46cb-9bf4-25be300317ff" /> | <img width="400" src="https://github.com/user-attachments/assets/4a14a730-4b13-4202-b2cf-0bdb1555bff4" /> |

**3. Payment & Confirmation**
*Includes robust error handling and printable receipts.*

| Secure Payment | Error Validation | Printable Receipt |
| :--- | :--- | :--- |
| <img width="300" src="https://github.com/user-attachments/assets/bf976877-a9db-417b-a2ff-7e298c6b5a25" /> | <img width="300" src="https://github.com/user-attachments/assets/c199d9f5-4a65-49d1-90b5-48e79f76bea0" /> | <img width="300" src="https://github.com/user-attachments/assets/84b41862-b6df-4999-b3e4-1ca0c1182df9" /> |

---

### 🛠 Admin Dashboard
*Complete control over the store's inventory.*

**Overview & Management**
![Page Overview](https://github.com/user-attachments/assets/934cbfdf-fcc8-40f8-ab0a-36c6da8294ab)

**Inventory Control**
| Add Product/Category | Edit Details |
| :--- | :--- |
| <img width="400" src="https://github.com/user-attachments/assets/b6176c04-97f7-451e-9552-8a6a74935efa" /> | <img width="400" src="https://github.com/user-attachments/assets/88703619-e01c-4416-ac85-b19c53106a6d" /> |

---

## 🛡️ Security Configuration

The application implements **Role-Based Access Control (RBAC)** to ensure data integrity:

| Action | Required Role | Method |
| :--- | :--- | :--- |
| View Catalog / Search | Public | `GET` |
| Add to Cart / Purchase | `ROLE_USER` | `POST` |
| Create/Edit/Delete | `ROLE_ADMIN` | `POST` (CSRF Protected) |

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
   git clone [https://github.com/MariemYahyaoui/Product_management.git](https://github.com/MariemYahyaoui/Product_management.git)
   cd product_management
