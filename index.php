<?php
$results = null;
$text = isset($_POST['text']) ? trim($_POST['text']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($text)) {
    $normalized = preg_replace('/\s+/', ' ', trim($text));

    // ✅ Character counts
    $char_count = strlen($normalized);
    $char_no_space = strlen(str_replace(' ', '', $normalized));

    // ✅ Word count (Unicode + hyphenated + number words)
    preg_match_all('/\b[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*\b/u', $normalized, $words);
    $word_count = count($words[0]);

    // ✅ Sentence and paragraph counts
    $sentence_count = preg_match_all('/[.!?]+(?=\s|$)/u', $normalized);
    $paragraph_count = substr_count(trim($text), "\n") + 1;

    // ✅ Time estimates
    $reading_time = ceil($word_count / 200);
    $speaking_time = ceil($word_count / 130);

    // ✅ Longest word logic
    $longest_word = '';
    foreach ($words[0] as $w) {
        if (strlen($w) > strlen($longest_word)) {
            $longest_word = $w;
        }
    }

    $results = [
        'words' => $word_count,
        'characters' => $char_count,
        'characters_no_space' => $char_no_space,
        'sentences' => $sentence_count,
        'paragraphs' => $paragraph_count,
        'reading_time' => $reading_time,
        'speaking_time' => $speaking_time,
        'longest_word' => $longest_word
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Counter Tool – IT Leadz</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/phosphor-icons"></script>
</head>
<body>
    <div class="container">
        <h1 class="main-title">✍️ Word Counter Tool</h1>
        <p class="sub">Get accurate counts like professional SEO platforms.</p>

        <form method="POST" class="counter-form">
            <textarea name="text" rows="8" placeholder="Paste or type your content here..." required><?php echo htmlspecialchars($text); ?></textarea>
            <button type="submit">Analyze Text</button>
        </form>

        <?php if ($results): ?>
        <div class="result-box">
            <h2 class="result-title">
                <span class="icon ph ph-chart-bar"></span> Analysis Summary
            </h2>
            <div class="metrics-grid">
                <div class="metric-card"><span class="icon ph ph-text-italic"></span><p>Words</p><h3><?php echo $results['words']; ?></h3></div>
                <div class="metric-card"><span class="icon ph ph-quotes"></span><p>Characters</p><h3><?php echo $results['characters']; ?></h3></div>
                <div class="metric-card"><span class="icon ph ph-space-between"></span><p>Characters (no space)</p><h3><?php echo $results['characters_no_space']; ?></h3></div>
                <div class="metric-card"><span class="icon ph ph-quotes"></span><p>Sentences</p><h3><?php echo $results['sentences']; ?></h3></div>
                <div class="metric-card"><span class="icon ph ph-paragraph"></span><p>Paragraphs</p><h3><?php echo $results['paragraphs']; ?></h3></div>
                <div class="metric-card"><span class="icon ph ph-clock-countdown"></span><p>Reading Time</p><h3><?php echo $results['reading_time']; ?> min</h3></div>
                <div class="metric-card"><span class="icon ph ph-microphone"></span><p>Speaking Time</p><h3><?php echo $results['speaking_time']; ?> min</h3></div>
                <div class="metric-card"><span class="icon ph ph-magnifying-glass-plus"></span><p>Longest Word</p><h3><?php echo htmlspecialchars($results['longest_word']); ?></h3></div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
