========================================
Legal Services Mobile App - Prototype
========================================

📌 Purpose of the App:
-----------------------
This mobile application is designed to improve access to legal services in Botswana. It allows users to:
- View legal information and resources
- Search for legal topics
- Book consultations with legal professionals
- Chat with legal service providers

The system includes:
- A **Flutter-based mobile frontend** (developed in Android Studio)
- A **PHP backend** with MySQL database (running via XAMPP)

===============================
🛠️ Required Software/Tools:
===============================
1. **Flutter SDK**
   - Install from: https://docs.flutter.dev/get-started/install
2. **Android Studio**
   - Required for running and testing the mobile app
3. **Visual Studio Code** (or any code editor)
4. **XAMPP** (for PHP and MySQL)
   - Install from: https://www.apachefriends.org/index.html

===============================
🚀 How to Run the Project:
===============================

1. **Setup the Backend (PHP & MySQL):**
   - Install and run XAMPP.
   - Copy the contents of the `Backend_PHP/` folder to `C:/xampp/htdocs/legal_services_backend/`.
   - Open **phpMyAdmin** (usually at http://localhost/phpmyadmin).
   - Create a database named: `legal_services`.
   - Import the file `legal_services_db.sql` (found in the `Database/` folder).

2. **Start the Apache and MySQL servers** from the XAMPP Control Panel.

3. **Test Backend APIs:**
   - Open a browser and go to:
     ```
     http://localhost/legal_services_backend/login.php
     http://localhost/legal_services_backend/getLegalInfo.php
     ```
   - Ensure the responses return without error.

4. **Run the Flutter App:**
   - Open `Frontend_Flutter/` in Android Studio.
   - Run `flutter pub get` in the terminal to install dependencies.
   - Connect an Android device or start an emulator.
   - Click the **Run** button to launch the app.

5. **Connect App to Backend:**
   - Ensure API URLs in your Flutter app (usually in a `config.dart` or similar file) point to:
     ```
     http://10.0.2.2/legal_services_backend/    ← for emulator
     OR
     http://<your local IP>/legal_services_backend/ ← for physical device
     ```

===============================
🔐 Default Test Credentials:
===============================
For testing login features (if implemented):

Email: testuser@example.com  
Password: password123  

*(You can create more users via phpMyAdmin if needed.)*

===============================
🔗 GitHub Repositories (Optional):
===============================
Frontend (Flutter): https://github.com/IAMTSHOLO/flutterApp.git 
Backend (PHP): https://github.com/IAMTSHOLO/my-php-project.git 

===============================
📞 Contact:
===============================
Student Name: [Tsholofelo Molefhe]  
Project Title: Legal Services Mobile App Prototype  
Email: [Victshomolf1999@gmail.com]  
Date: [Submission Date]  
