
<?php
echo "<script>
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'hospital_logout_session.php';
    } else {
        window.location.href = 'hospital_home_page.html'; 
    }
</script>";

?>
