
<div class='flex-container'>
<form action="index.php" method="POST">
<h2>Manage Users</h2>
    <input type="hidden" name="action" value="update_userLevel">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="userlevel">User Level:</label>
    <select id="userlevel" name="userlevel">
        <option value="user">User</option>
        <option value="manager">Manager</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit">Update User</button>
</form>
</div>