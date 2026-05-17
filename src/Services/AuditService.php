<?php

declare(strict_types=1);

namespace WordpressBlockSeoGovernanceAuditor\Services;

final class AuditService
{
    private array $data;

    public function __construct()
    {
        /** @var array $data */
        $data = require __DIR__ . '/../Data/sample_audits.php';
        $this->data = $data;
    }

    public function pages(): array
    {
        $pages = $this->data['pages'];
        usort(
            $pages,
            static fn (array $left, array $right): int => self::statusRank($left['status']) <=> self::statusRank($right['status']),
        );

        return $pages;
    }

    public function schemaOpportunities(): array
    {
        return $this->data['schemaOpportunities'];
    }

    public function verificationLanes(): array
    {
        return $this->data['verificationLanes'];
    }

    public function summary(): array
    {
        $pages = $this->data['pages'];
        $pageCount = count($pages);
        $breakingCount = count(array_filter($pages, static fn (array $page): bool => $page['status'] === 'breaking'));
        $watchCount = count(array_filter($pages, static fn (array $page): bool => $page['status'] === 'watch'));
        $healthyCount = count(array_filter($pages, static fn (array $page): bool => $page['status'] === 'healthy'));
        $averageAnswerScore = (int) round(array_sum(array_column($pages, 'answerScore')) / max($pageCount, 1));

        return [
            'pageCount' => $pageCount,
            'breakingCount' => $breakingCount,
            'watchCount' => $watchCount,
            'healthyCount' => $healthyCount,
            'averageAnswerScore' => $averageAnswerScore,
            'leadRecommendation' => 'Fix the inspiration and renter-focused pages first, because those are the surfaces most likely to leak weak headings and thin answers into AI-facing retrieval.',
        ];
    }

    public function payload(): array
    {
        return [
            'summary' => $this->summary(),
            'pages' => $this->pages(),
            'schemaOpportunities' => $this->schemaOpportunities(),
            'verificationLanes' => $this->verificationLanes(),
        ];
    }

    private static function statusRank(string $status): int
    {
        return match ($status) {
            'breaking' => 0,
            'watch' => 1,
            default => 2,
        };
    }
}
