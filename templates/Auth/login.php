<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<h2>Login</h2>
<form method="post" action="/login">
    <label>Usuario: <input name="username" required></label><br>
    <label>Clave: <input name="password" type="password" required></label><br>
    <button type="submit">Ingresar</button>
</form>
<p><small>Demo: admin/admin</small></p>
