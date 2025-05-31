<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "ottostore");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// DELETE STORE
if (isset($_GET['delete'])) {
    $delete_name = urldecode($_GET['delete']);
    $delete_name = mysqli_real_escape_string($con, $delete_name);
    mysqli_query($con, "DELETE FROM stores WHERE store_name = '$delete_name'");
    header("Location: staff_editor.php");
    exit;
}

// ADD or UPDATE STORE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $store_name = mysqli_real_escape_string($con, $_POST['store_name']);
    $region = mysqli_real_escape_string($con, $_POST['region']);
    $address = mysqli_real_escape_string($con, $_POST['Address']);
    $state = mysqli_real_escape_string($con, $_POST['State']);
    $pin = mysqli_real_escape_string($con, $_POST['Pin']);
    $phone = mysqli_real_escape_string($con, $_POST['Phone']);

    if (isset($_POST['edit_store_name'])) {
        $edit_name = mysqli_real_escape_string($con, $_POST['edit_store_name']);
        mysqli_query($con, "UPDATE stores SET 
            store_name = '$store_name', 
            region = '$region', 
            Address = '$address', 
            State = '$state', 
            Pin = '$pin', 
            Phone = '$phone'
            WHERE store_name = '$edit_name'");
    } else {
        mysqli_query($con, "INSERT INTO stores 
            (store_name, region, Address, State, Pin, Phone) VALUES 
            ('$store_name', '$region', '$address', '$state', '$pin', '$phone')");
    }

    header("Location: staff_editor.php");
    exit;
}

// FETCH STORE DATA FOR EDIT
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_name = urldecode($_GET['edit']);
    $edit_name = mysqli_real_escape_string($con, $edit_name);
    $result = mysqli_query($con, "SELECT * FROM stores WHERE store_name = '$edit_name'");
    $edit_data = mysqli_fetch_assoc($result);
}

// HANDLE SEARCH
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = mysqli_real_escape_string($con, $_GET['search']);
}

// FETCH STORES (with optional search filter)
if ($search_term !== '') {
    $stores = mysqli_query($con, "SELECT * FROM stores WHERE store_name LIKE '%$search_term%' ORDER BY store_name ASC");
} else {
    $stores = mysqli_query($con, "SELECT * FROM stores ORDER BY store_name ASC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Store Editor</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #999; text-align: left; }
        form { margin-bottom: 20px; }
        input, button { padding: 8px; margin: 5px 0; width: 300px; }
        .actions a { margin-right: 10px; text-decoration: none; }
        .edit-btn { color: green; }
        .delete-btn { color: red; }
        #searchForm { margin-bottom: 10px; }
    </style>
</head>
<body>

<h2><?= $edit_data ? "Edit Store" : "Add New Store" ?></h2>
  
  <a href="index.html" style="display: inline-block; margin-bottom: 10px; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">← Back to Dashboard</a>

<form method="POST">
    <input type="text" name="store_name" placeholder="Store Name" required value="<?= htmlspecialchars($edit_data['store_name'] ?? '') ?>"><br>
    <input type="text" name="region" placeholder="Region" required value="<?= htmlspecialchars($edit_data['region'] ?? '') ?>"><br>
    <input type="text" name="Address" placeholder="Address" value="<?= htmlspecialchars($edit_data['Address'] ?? '') ?>"><br>
    <input type="text" name="State" placeholder="State" value="<?= htmlspecialchars($edit_data['State'] ?? '') ?>"><br>
    <input type="text" name="Pin" placeholder="Pin Code" value="<?= htmlspecialchars($edit_data['Pin'] ?? '') ?>"><br>
    <input type="text" name="Phone" placeholder="Phone Number" value="<?= htmlspecialchars($edit_data['Phone'] ?? '') ?>"><br>

    <?php if ($edit_data): ?>
        <input type="hidden" name="edit_store_name" value="<?= htmlspecialchars($edit_data['store_name']) ?>">
        <button type="submit">Update Store</button>
        <a href="staff_editor.php">Cancel</a>
    <?php else: ?>
        <button type="submit">Add Store</button>
    <?php endif; ?>
</form>

<hr>

<h2>Store List</h2>

<!-- Search Form -->
<form id="searchForm" method="GET" action="staff_editor.php">
    <input type="text" name="search" placeholder="Search store name..." value="<?= htmlspecialchars($search_term) ?>">
    <button type="submit">Search</button>
    <?php if ($search_term !== ''): ?>
        <a href="staff_editor.php">Clear Search</a>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>Store Name</th>
        <th>Region</th>
        <th>Address</th>
        <th>State</th>
        <th>Pin</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($stores)) {
        $store_name_encoded = urlencode($row['store_name']);
        echo "<tr>
                <td>" . htmlspecialchars($row['store_name']) . "</td>
                <td>" . htmlspecialchars($row['region']) . "</td>
                <td>" . htmlspecialchars($row['Address']) . "</td>
                <td>" . htmlspecialchars($row['State']) . "</td>
                <td>" . htmlspecialchars($row['Pin']) . "</td>
                <td>" . htmlspecialchars($row['Phone']) . "</td>
                <td class='actions'>
                    <a class='edit-btn' href='staff_editor.php?edit={$store_name_encoded}'>✏️ Edit</a>
                    <a class='delete-btn' href='staff_editor.php?delete={$store_name_encoded}' onclick='return confirm(\"Delete this store?\")'>🗑️ Delete</a>
                </td>
            </tr>";
    }
    ?>
</table>
</body>
</html>
