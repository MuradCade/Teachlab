<?php
// Function to calculate subscription end date and remaining days
function calculateSubscription($startDate, $subscriptionType = 'monthly') {
    $start = new DateTime($startDate);
    $end = clone $start;
    
    // Add subscription duration based on type
    if ($subscriptionType === 'monthly') {
        $end->modify('+1 month'); // Add 1 month
    } elseif ($subscriptionType === 'yearly') {
        $end->modify('+1 year'); // Add 1 year
    }
    
    // Calculate remaining days
    $currentDate = new DateTime();
    $remainingDays = $currentDate->diff($end)->days;
    
    return [
        'start_date' => $start->format('Y-m-d'),
        'end_date' => $end->format('Y-m-d'),
        'remaining_days' => $remainingDays
    ];
}