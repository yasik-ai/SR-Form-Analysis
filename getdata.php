<?php

$filename = "contact_data.csv";

echo "<html><body><table>\n\n";

if (file_exists($filename)) {
$f = fopen($filename, "r");
while (($line = fgetcsv($f)) !== false) {
        echo "<tr>";
        foreach ($line as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>\n";
}
fclose($f);
}

else{ echo "<tr><td>No file exists ! </td></tr>" ;}
echo "\n</table></body></html>";
?>
