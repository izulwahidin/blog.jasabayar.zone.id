<?php
$targetDir = "D:\_IZUL\Hugo New\myblog\content\posts";
$postJSON = glob("D:\_IZUL\PHP\Free Gemini AI Articles\content\*.json");

foreach ($postJSON as $post) {
    $post = json_decode(file_get_contents($post));
    $toSave = "$targetDir/{$post->response->outline->slug}.md";
    if (is_file($toSave)) {
        echo "Skipped {$post->response->outline->title}\n";
        continue;
    }


    $title = json_encode($post->response->outline->title);
    $description = json_encode($post->response->outline->meta_description);
    $categories = json_encode($post->response->outline->category);
    $date = generateRandomDate();
    $save = <<<s
    ---
    title: $title
    date: $date
    description: $description
    categories: $categories
    ---
    
    {$post->response->article}
    s;

    file_put_contents($toSave, $save);

    echo "Saved {$post->response->outline->title}\n";
}


function generateRandomDate($startDate = '-30 days', $endDate = 'now')
{
    // 1. Convert relative strings to Unix timestamps
    $start_timestamp = strtotime($startDate);
    $end_timestamp = strtotime($endDate);

    // 2. Generate a random timestamp between the min and max
    $random_timestamp = mt_rand($start_timestamp, $end_timestamp);

    // 3. Format the random timestamp into the ISO 8601 format Hugo uses
    // 'c' is the standard PHP format character for ISO 8601
    return date('c', $random_timestamp);
}
