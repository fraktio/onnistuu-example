<style>
body {display: flex; justify-content: center;}
form {flex: 1; max-width: 400px;}
form * {display: block; width: 100%; margin: 0;}
form p, form button { margin-top: 10px;}
</style>

<form method="POST" action="sign.php">
    <p>Nimi</p>
    <input type="text" name="name" />

    <p>Jne.</p>
    <textarea name="extra"></textarea>

    <p>Henkilötunnus</p>
    <input type="text" name="identifier" />

    <button type="submit">Allekirjoita</button>
</form>

