<?php

include 'condb.php';

$action = $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action) {
    // เพิ่ม / แก้ไข / ลบ
    switch($action) {

        case 'add':
            $emp_id = $_POST['emp_id'] ?? null;
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];

            // ตรวจสอบ emp_id ซ้ำ (ถ้ามี)
            if ($emp_id) {
                $checkStmt = $conn->prepare("SELECT emp_id FROM employees WHERE emp_id = :emp_id");
                $checkStmt->bindParam(':emp_id', $emp_id);
                $checkStmt->execute();
                if ($checkStmt->rowCount() > 0) {
                    echo json_encode(["error" => "รหัสพนักงานนี้มีอยู่ในระบบแล้ว"]);
                    exit;
                }
            }

            // อัพโหลดไฟล์รูป
            $filename = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = "uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            $sql = "INSERT INTO employees (emp_id, first_name, last_name, address, phone, image)
                    VALUES (:emp_id, :first_name, :last_name, :address, :phone, :image)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':emp_id', $emp_id);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':image', $filename);

            if ($stmt->execute()) {
                echo json_encode(["message" => "เพิ่มพนักงานสำเร็จ"]);
            } else {
                echo json_encode(["error" => "เพิ่มพนักงานล้มเหลว"]);
            }
            break;

        case 'update':
            $emp_id = $_POST['emp_id'];  // ใช้ emp_id แทน id
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];

            // ตรวจสอบ emp_id ซ้ำ (ยกเว้นตัวเอง)
            $checkStmt = $conn->prepare("SELECT emp_id FROM employees WHERE emp_id = :check_emp_id AND emp_id != :emp_id");
            $check_emp_id = $_POST['new_emp_id'] ?? $emp_id;  // ถ้ามีการเปลี่ยน emp_id
            $checkStmt->bindParam(':check_emp_id', $check_emp_id);
            $checkStmt->bindParam(':emp_id', $emp_id);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                echo json_encode(["error" => "รหัสพนักงานนี้มีอยู่ในระบบแล้ว"]);
                exit;
            }

            // อัพโหลดไฟล์รูป
            $imageSQL = "";
            $filename = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = "uploads/";
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $imageSQL = ", image = :image";
            }

            $sql = "UPDATE employees SET 
                        first_name = :first_name,
                        last_name = :last_name,
                        address = :address,
                        phone = :phone
                        $imageSQL
                    WHERE emp_id = :emp_id";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':emp_id', $emp_id);
            if ($filename) {
                $stmt->bindParam(':image', $filename);
            }

            if ($stmt->execute()) {
                echo json_encode(["message" => "แก้ไขพนักงานสำเร็จ"]);
            } else {
                echo json_encode(["error" => "แก้ไขพนักงานล้มเหลว"]);
            }
            break;

        case 'delete':
            $emp_id = $_POST['emp_id'];  // ใช้ emp_id แทน id
            
            // ดึงข้อมูลรูปภาพก่อนลบ
            $stmt = $conn->prepare("SELECT image FROM employees WHERE emp_id = :emp_id");
            $stmt->bindParam(':emp_id', $emp_id);
            $stmt->execute();
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ลบข้อมูลจากฐานข้อมูล
            $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :emp_id");
            $stmt->bindParam(':emp_id', $emp_id);
            
            if ($stmt->execute()) {
                // ลบไฟล์รูปภาพ (ถ้ามี)
                if ($employee && !empty($employee['image'])) {
                    $file_path = "uploads/" . $employee['image'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
                echo json_encode(["message" => "ลบพนักงานสำเร็จ"]);
            } else {
                echo json_encode(["error" => "ลบพนักงานล้มเหลว"]);
            }
            break;

        default:
            echo json_encode(["error" => "Action ไม่ถูกต้อง"]);
            break;
    }

} else {
    // GET: ดึงข้อมูลพนักงาน
    $stmt = $conn->prepare("SELECT emp_id, first_name, last_name, address, phone, image FROM employees ORDER BY emp_id DESC");
    if ($stmt->execute()) {
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $employees]);
    } else {
        echo json_encode(["success" => false, "data" => []]);
    }
}
?>