<?php   

if (empty($_SESSION['ulogovan']) || $_SESSION['ulogovan'] !== 1){
    die('Zabranjen pristup');
}
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;
$id = $_GET['id'] ?? 0; // id proizvoda koji želimo urediti, npr. index.php?page=adminPanel&view=update&id=2
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$category = $_POST['category'] ?? '';

    // Ažuriranje baze
    $stmt = $conn->prepare("UPDATE news SET title=?, content=?, category=? WHERE id=?");
    $stmt->bind_param("sssi", $title, $content, $category, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?page=news&id=$id");
        
        exit;
    } else {
        echo "Greška: " . $stmt->error;
    }

?>
