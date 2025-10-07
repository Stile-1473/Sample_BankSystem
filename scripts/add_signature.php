<?php
// Usage (Windows CMD):
//   cd c:\\xamppp\\htdocs\\Group && php scripts\\add_signature.php
// If PHP is not in PATH, use full path, e.g.:
//   cd c:\\xamppp\\htdocs\\Group && "C:\\xampp\\php\\php.exe" scripts\\add_signature.php
// Optional flags: --dry-run (no writes), --verbose

error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(0);

$signature = 'Creator: ghost1473';
$dryRun = in_array('--dry-run', $argv ?? []);
$verbose = in_array('--verbose', $argv ?? []);

$commentStyles = [
    'php' => 'php',
    'phtml' => 'html',
    'html' => 'html', 'htm' => 'html', 'xml' => 'html',
    'css' => 'block', 'js' => 'block',
    'sql' => 'sql',
    'md' => 'plain', 'txt' => 'plain', 'ini' => 'plain', 'env' => 'plain'
];

$root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..');
if ($root === false) {
    fwrite(STDERR, "Cannot resolve project root\n");
    exit(1);
}

$excludeDirs = [
    '.git', '.qodo', 'vendor', 'node_modules',
    'assets' . DIRECTORY_SEPARATOR . 'img',
    // Skip large offline docs assets subtrees
    'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap-offline-docs-5.1',
];

$processed = 0; $skipped = 0; $errors = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $fileInfo) {
    if (!$fileInfo->isFile()) continue;
    $path = $fileInfo->getPathname();
    $rel  = substr($path, strlen($root) + 1);

    // Skip excluded directories
    $skip = false;
    foreach ($excludeDirs as $ex) {
        if (strpos($rel, $ex . DIRECTORY_SEPARATOR) === 0 || $rel === $ex) { $skip = true; break; }
    }
    if ($skip) { $skipped++; continue; }

    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!isset($commentStyles[$ext])) { $skipped++; continue; }

    // Don't modify this script itself
    if (preg_match('#scripts' . preg_quote(DIRECTORY_SEPARATOR, '#') . 'add_signature\.php$#i', $rel)) { $skipped++; continue; }

    $content = @file_get_contents($path);
    if ($content === false) { $errors++; if ($verbose) fwrite(STDERR, "Read failed: $rel\n"); continue; }

    // If signature already exists near the top, skip
    $head = substr($content, 0, 800);
    if (stripos($head, $signature) !== false) { $skipped++; continue; }

    $newContent = addSignature($content, $commentStyles[$ext], $signature, $verbose, $rel);
    if ($newContent === null) { $skipped++; continue; }

    if ($dryRun) { $processed++; if ($verbose) echo "[DRY] Would update: $rel\n"; continue; }

    if (@file_put_contents($path, $newContent) === false) { $errors++; if ($verbose) fwrite(STDERR, "Write failed: $rel\n"); continue; }
    $processed++;
    if ($verbose) echo "Updated: $rel\n";
}

echo "Signature injection complete. Processed: {$processed}, Skipped: {$skipped}, Errors: {$errors}\n";

function addSignature(string $content, string $style, string $signature, bool $verbose, string $rel): ?string {
    // Normalize to preserve UTF-8 BOM if present
    $bom = '';
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") { $bom = substr($content, 0, 3); $content = substr($content, 3); }

    switch ($style) {
        case 'php':
            // Insert after the first '<?php' occurrence, regardless of leading whitespace/BOM
            $pos = stripos($content, '<?php');
            if ($pos !== false) {
                $posEnd = $pos + 5; // length of '<?php'
                // Insert a newline and signature comment after opening tag
                $injected = substr($content, 0, $posEnd) . "\n// {$signature}\n" . substr($content, $posEnd);
                return $bom . $injected;
            } else {
                // If it's a PHP file containing pure HTML, add an HTML comment instead to avoid breaking output
                return $bom . "<!-- {$signature} -->\n" . $content;
            }
        case 'html':
            // If DOCTYPE or XML declaration exists, place signature after the first line
            if (preg_match('/^(<!DOCTYPE[^>]*>\s*\n|<\?xml[^>]*>\s*\n)/i', $content, $m)) {
                $offset = strlen($m[0]);
                return $bom . substr($content, 0, $offset) . "<!-- {$signature} -->\n" . substr($content, $offset);
            }
            return $bom . "<!-- {$signature} -->\n" . $content;
        case 'block': // css/js
            return $bom . "/* {$signature} */\n" . $content;
        case 'sql':
            return $bom . "-- {$signature}\n" . $content;
        case 'plain': // md/txt/env
            return $bom . "{$signature}\n" . $content;
        default:
            if ($verbose) fwrite(STDERR, "Unsupported style for {$rel}\n");
            return null;
    }
}



// Creator: ghost1473

// Usage (Windows CMD):
//   cd c:\\xamppp\\htdocs\\Group && php scripts\\add_footer_signature.php --verbose
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
