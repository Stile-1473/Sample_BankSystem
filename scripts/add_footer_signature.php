<?php
// Creator: ghost1473

// Usage (Windows CMD):
// cd c:\\xamppp\\htdocs\\Group && php scripts\\add_footer_signature.php --verbose
// Adds a visible footer signature “Signature: Ghost1473 — ZeroDaySolutions” to all HTML/PHP view files.
// It injects the signature inside an existing <footer>…</footer>, or creates one before </body> when missing.

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);

$signatureText = 'Signature: Ghost1473 — ZeroDaySolutions';
$dryRun = in_array('--dry-run', $argv ?? []);
$verbose = in_array('--verbose', $argv ?? []);

$root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');
$viewsDir = $root . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views';
if (!is_dir($viewsDir)) { fwrite(STDERR, "Views directory not found: {$viewsDir}\n"); exit(1); }

$processed = 0; $skipped = 0; $errors = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir, FilesystemIterator::SKIP_DOTS));
foreach ($it as $fileInfo) {
    if (!$fileInfo->isFile()) continue;
    $path = $fileInfo->getPathname();
    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['php','html','htm'])) { $skipped++; continue; }

    $content = @file_get_contents($path);
    if ($content === false) { $errors++; if ($verbose) fwrite(STDERR, "Read failed: {$path}\n"); continue; }

    // Skip if already contains the visible signature text
    if (stripos($content, $signatureText) !== false) { $skipped++; continue; }

    $newContent = injectFooterSignature($content, $signatureText);
    if ($newContent === $content) { $skipped++; continue; }

    if ($dryRun) { $processed++; if ($verbose) echo "[DRY] Would update: {$path}\n"; continue; }

    if (@file_put_contents($path, $newContent) === false) { $errors++; if ($verbose) fwrite(STDERR, "Write failed: {$path}\n"); continue; }
    $processed++;
    if ($verbose) echo "Updated: {$path}\n";
}

echo "Footer signature injection complete. Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}\n";

function injectFooterSignature(string $content, string $signatureText): string {
    $sigBlock = "<div class=\"container\" style=\"padding:.75rem 0; text-align:center; color:var(--muted);\"><small>{$signatureText}</small></div>";

    // Try to insert inside an existing footer (before </footer>)
    $footerClosePattern = '/<\\/footer>/i';
    if (preg_match($footerClosePattern, $content)) {
        return preg_replace($footerClosePattern, $sigBlock . "\n</footer>", $content, 1);
    }

    // Otherwise, create a simple footer before </body>
    $newFooter = "<footer style=\"border-top:1px solid var(--border);\">\n  {$sigBlock}\n</footer>\n";
    $bodyClosePattern = '/<\\/body>/i';
    if (preg_match($bodyClosePattern, $content)) {
        return preg_replace($bodyClosePattern, $newFooter . "</body>", $content, 1);
    }

    // If no </body>, append at end (as a last resort)
    return $content . "\n" . $newFooter;
}
