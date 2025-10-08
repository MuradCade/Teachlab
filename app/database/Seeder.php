<?php

namespace App\database;

use App\Models\Users;
use App\Models\UserSubscription;
use App\Models\VerifyEmail;
use App\Models\MailQueueu;

require __DIR__ . '/../../vendor/autoload.php';

$config = require __DIR__ . '/../../env.php';
\App\Config\Eloquent::setup($config);

// -------------------------
// Seeder script
// -------------------------

echo "Seeding database...\n";

$email = 'test@example.com';
$userid = rand(100, 99999);

// 1. Create a default test user
$user = Users::create([
    'userid' => $userid,
    'fullname' => 'Existing User',
    'email' => $email,
    'password' => password_hash('Test#1212', PASSWORD_DEFAULT),
]);

// 2. Create corresponding subscription
UserSubscription::create([
    'userid' => $user->userid,
    'plan' => 'free', // adjust according to your schema
]);

// 3. Create verification token
VerifyEmail::create([
    'userid' => $user->userid,
    'token' => bin2hex(random_bytes(16)),
    'is_used' => 0,
]);

// 4. Queue signup email
MailQueueu::create([
    'userid' => $user->userid,
    'username' => $user->fullname,
    'useremail' => $user->email,
    'mail_type' => 'signup',
    'mail_status' => 'pending',
]);

echo "Seeding completed!\n";
