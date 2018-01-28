<?php

include '../../../lib/dbinfo.inc.php';
include '../../../lib/FunctionAct.php';
$username = $_POST['username'];
$pass = $_POST['password'];
$s = oci_parse($conn, 'select * FROM LJ_USER_LOGIN where USER_NAME = :un_bv and USER_PASS = :pw_bv and ISACTIVE = 1');
oci_bind_by_name($s, ":un_bv", $username);
oci_bind_by_name($s, ":pw_bv", $pass);
//oci_bind_by_name($s, ":ia", '1');
oci_execute($s);
$r = oci_fetch_array($s, OCI_ASSOC);

if ($r) {
    $user_id = $r['USER_ID'];
    $role = $r['ROLE'];
    session_start();
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    echo ('<script>location.href="/LautanJati/"</script>');
} else {
    // No rows matched so login failed
    echo ('<script>alert("GAGAL LOGIN !!! \nMASUKKAN USER DAN PASSWORD DENGAN BENAR")</script>');
    echo ('<script>location.href="../../../login.html"</script>');
}