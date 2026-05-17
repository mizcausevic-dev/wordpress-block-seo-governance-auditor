<?php

declare(strict_types=1);

$demo = [
    'product' => 'WordPress Block SEO Governance Auditor',
    'purpose' => 'PHP control plane for auditing WordPress block hierarchy, answer-surface readiness, and schema opportunities.',
    'routes' => ['/', '/block-audit', '/schema-opportunities', '/verification', '/docs'],
    'priorities' => [
        'Find pages where block order weakens answer-engine readability',
        'Separate breaking, watch, and healthy editorial surfaces',
        'Expose FAQ and schema opportunities at the block level',
        'Turn WordPress content governance into a measurable operator lane',
    ],
];

echo json_encode($demo, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
