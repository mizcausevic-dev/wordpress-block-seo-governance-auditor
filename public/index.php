<?php

declare(strict_types=1);

use WordpressBlockSeoGovernanceAuditor\Services\AuditService;

require __DIR__ . '/../src/Services/AuditService.php';
require __DIR__ . '/../src/Views/render.php';

$service = new AuditService();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if (str_starts_with($path, '/api/')) {
    header('Content-Type: application/json; charset=utf-8');

    $payload = match ($path) {
        '/api/summary' => $service->summary(),
        '/api/pages' => $service->pages(),
        '/api/schema-opportunities' => $service->schemaOpportunities(),
        '/api/verification' => $service->verificationLanes(),
        '/api/sample' => $service->payload(),
        default => ['error' => 'Not found'],
    };

    if ($payload === ['error' => 'Not found']) {
        http_response_code(404);
    }

    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    return;
}

$html = match ($path) {
    '/' => WordpressBlockSeoGovernanceAuditor\Views\render_overview(),
    '/block-audit' => WordpressBlockSeoGovernanceAuditor\Views\render_block_audit(),
    '/schema-opportunities' => WordpressBlockSeoGovernanceAuditor\Views\render_schema_opportunities(),
    '/verification' => WordpressBlockSeoGovernanceAuditor\Views\render_verification(),
    '/docs' => WordpressBlockSeoGovernanceAuditor\Views\render_docs(),
    default => null,
};

if ($html === null) {
    http_response_code(404);
    echo 'Not found';
    return;
}

header('Content-Type: text/html; charset=utf-8');
echo $html;
