<?php if (($_SESSION['status'] ?? '') === 'login'): ?>
    <script>

        setTimeout(function() {
            window.location.replace('login.php?error=expired');
        }, <?= max(0, ($_SESSION['login_at'] + 300 - time()) * 1000) ?>);
    </script>
<?php endif; ?>