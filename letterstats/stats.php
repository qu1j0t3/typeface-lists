<?php

// $f = fopen('Aspects of the novel  Author- E. M. Forster.txt', 'r');
$f = fopen('Moby Dick; Or, The Whale  Author- Herman Melville.txt', 'r');
assert($f !== false);

$freq = [];
while(($c = fgetc($f)) !== false) {
    $lc = strtolower($c);
    if (ctype_alpha($c)) {
        $freq[$lc] = ($freq[$lc] ?? 0) + 1;
    }
}

asort($freq);

$freqdesc = array_reverse($freq);
print_r($freqdesc);
echo count($freqdesc), "\n\n";

rewind($f);

$pairs = [];
$revpairs = [];
for($i = 0; ($line = fgets($f)) !== false; ++$i) {
    $lc = strtolower($line);
    for($j = 0; $j < strlen($lc)-1; ++$j) {
        $a = $lc[$j];
        $b = $lc[$j+1];
        if (ctype_alpha($a) && ctype_alpha($b)) {
            $pairs[$a][$b] ??= 0;
            ++ $pairs[$a][$b];
            $revpairs[$b][$a] ??= 0;
            ++ $revpairs[$b][$a];
        }
    }
}

foreach([false,true] as $upper) {
    foreach($freqdesc as $letter => $n) {
        $next = $pairs[$letter] ?? [];
        asort($next);
        //echo "$letter : ", json_encode(array_reverse($next)), "\n";

        // For each letter, produce an interleaved line
        // LaLbLcLdLeLfLg...
        // where L is the subject letter
        // a,b,c,d,e,f,g are the pairings selected from $pairs[L]

        $str = "$letter : $letter" . implode($letter, array_keys($next)) . $letter;

        echo $upper ? strtoupper($str) : $str, "\n";
    }

    foreach($freqdesc as $letter => $n) {
        $str = "$letter : $letter" . implode(" $letter", ['.',',',':',';','”','’','-',')',']','!','?', "'", '"']);
        echo $upper ? strtoupper($str) : $str, "\n";
        $str = "$letter : " . implode("$letter ", ['“', '‘', '(', '[', "'", '"']) . $letter;
        echo $upper ? strtoupper($str) : $str, "\n";
    }

    foreach(range(0, 9) as $a) {
        echo "$a : $a", implode("$a", array_merge(range(0, 9), ['.',',','-'])), "$a\n";
    }
}


echo "\n\n";

