<?php
session_start();

function generateRandomGuess() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

if (!isset($_SESSION['aiGuess']) || !isset($_SESSION['startTime'])) {
    // Generate a new random AI guess if not set
    $_SESSION['aiGuess'] = generateRandomGuess();
    $_SESSION['startTime'] = time();
}

$userGuess = isset($_POST['userGuess']) ? strval($_POST['userGuess']) : '';

if (!empty($userGuess)) {
    $aiGuess = $_SESSION['aiGuess'];
    $startTime = $_SESSION['startTime'];

    if ($userGuess === $aiGuess) {
        $response = ['correct' => true, 'message' => 'Congratulations! You guessed it!', 'aiGuess' => $aiGuess];
        unset($_SESSION['aiGuess'], $_SESSION['startTime']); // End the current game
    } else {
        $response = ['correct' => false, 'message' => 'Incorrect guess. Try again.', 'aiGuess' => $aiGuess];
    }

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid guess.']);
}

// Reset the game after 30 seconds or generate a new AI guess
if (time() - $startTime > 30) {
    $_SESSION['aiGuess'] = generateRandomGuess();
    unset($_SESSION['startTime']);
    echo json_encode(['timeout' => true, 'message' => 'Time expired. Game reset.', 'aiGuess' => $_SESSION['aiGuess']]);
}
?>
