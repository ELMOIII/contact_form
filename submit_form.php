<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_us_db";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Menggunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // Menampilkan pesan berhasil
        echo "Message sent successfully!<br>";

        // Ambil ID terakhir yang baru dimasukkan
        $last_id = $conn->insert_id;

        // Query untuk mengambil data berdasarkan ID terakhir yang dimasukkan
        $sql = "SELECT * FROM messages WHERE id = $last_id";
        $result = $conn->query($sql);

        // Periksa apakah data berhasil diambil
        if ($result->num_rows > 0) {
            // Tampilkan data yang baru dimasukkan
            $row = $result->fetch_assoc();
            echo "Here is the data you just submitted:<br>";
            echo "Name: " . $row["name"] . "<br>";
            echo "Email: " . $row["email"] . "<br>";
            echo "Subject: " . $row["subject"] . "<br>";
            echo "Message: " . $row["message"] . "<br>";
        } else {
            echo "No data found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
