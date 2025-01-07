<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pemilihan'];

    if (!empty($id)) {
        try {
            // Start a transaction (optional, but recommended for multiple operations)
            $conn->begin_transaction();

            // Delete the event
            $sql = "DELETE FROM pemilihan WHERE id_pemilihan = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // Commit the transaction if successful
                $conn->commit();
                header("Location: event.php?message=success");
            } else {
                // Rollback the transaction if there's an error
                $conn->rollback();
                header("Location: event.php?message=error");
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // Catch the exception and redirect with a custom error message
            $conn->rollback(); // Rollback the transaction
            header("Location: event.php?message=foreign_key_error");
        }
    } else {
        header("Location: adminpanel.php?message=invalid_id");
    }
} else {
    header("Location: adminpanel.php?message=invalid_method");
}
$conn->close();
?>