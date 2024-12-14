<?php

// Define the evaluateQuiz function
function evaluateQuiz(array $questions, array $answers): int {
    $score = 0;

    // Compare each answer with the correct one
    foreach ($questions as $index => $question) {
        if (isset($answers[$index]) && strtolower(trim($answers[$index])) === strtolower(trim($question['correct']))) {
            $score++;
        }
    }

    return $score;
}

// Define the quiz questions
$questions = [
    ['question' => 'What is 2 + 2?', 'correct' => '4'],
    ['question' => 'What is the capital of France?', 'correct' => 'Paris'],
    ['question' => 'Who wrote "Hamlet"?', 'correct' => 'Shakespeare'],
];

// Collect answers from the user
$answers = [];
foreach ($questions as $index => $question) {
    echo ($index + 1) . '. ' . $question['question'] . PHP_EOL;
    $answers[] = readline("Your answer: ");
}

// Evaluate the user's score
$totalQuestions = count($questions);
$score = evaluateQuiz($questions, $answers);

// Display the score
echo "You scored $score out of $totalQuestions." . PHP_EOL;

// Provide feedback based on performance
if ($score === $totalQuestions) {
    echo "Excellent job!" . PHP_EOL;
} elseif ($score > ($totalQuestions / 2)) {
    echo "Good effort!" . PHP_EOL;
} else {
    echo "Better luck next time!" . PHP_EOL;
}
