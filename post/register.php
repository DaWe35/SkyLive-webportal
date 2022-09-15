<?php

exit("The registration is no longer available");

if (empty($_POST['password'])):
    exit('Password is empty');
elseif (empty($_POST['email'])):
    exit('Email is empty');
elseif (empty($_POST['name'])):
    exit('Name is empty');
endif;

$stmt = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->execute(array($_POST['email']));
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($row):
    exit('Email already used');
elseif (strlen($_POST['password']) < 6):
    exit("Password is too weak!");
elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)):
    exit($_POST['email']." isn't a valid email");
endif;

// insert user
$options = [
    'cost' => 10,
];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

$regtime=time();
$name = htmlspecialchars($_POST['name']);

$sqlData = [
    'email' => $_POST['email'],
    'password' => $password,
    'name' => $name,
    'regtime' => $regtime
];
$sql = "INSERT INTO users (email, password, name, reg_time) VALUES (:email, :password, :name, :regtime)";
$stmt= $db->prepare($sql);

if ($stmt->execute($sqlData)) {
    // log in user
    session_start();
    $_SESSION['id'] = $db->lastInsertId();
    $_SESSION['rank'] = 'unverified';
    $_SESSION['name'] = $name;
    header("Location: studio");
} else {
    echo 'SQL error: sign up insert failed';
}