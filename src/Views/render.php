<?php

declare(strict_types=1);

namespace WordpressBlockSeoGovernanceAuditor\Views;

use WordpressBlockSeoGovernanceAuditor\Services\AuditService;

function render_shell(string $active, string $title, string $intro, string $content): string
{
    $service = new AuditService();
    $summary = $service->summary();
    $links = [
        '/' => 'Overview',
        '/block-audit' => 'Block audit',
        '/schema-opportunities' => 'Schema opportunities',
        '/verification' => 'Verification',
        '/docs' => 'Docs',
    ];

    $nav = '';
    foreach ($links as $href => $label) {
        $class = $href === $active ? 'nav-link is-active' : 'nav-link';
        $nav .= sprintf('<a class="%s" href="%s">%s</a>', $class, $href, $label);
    }

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{$title}</title>
    <style>
      :root {
        color-scheme: light;
        --bg: #f4f6fb;
        --panel: rgba(255, 255, 255, 0.88);
        --panel-strong: #ffffff;
        --line: rgba(35, 63, 113, 0.12);
        --text: #182238;
        --muted: #627396;
        --accent: #2f6af5;
        --accent-soft: #7ec6ff;
        --bad: #e05f76;
        --warn: #d1a446;
        --good: #2a9f70;
        --shadow: 0 22px 60px rgba(26, 47, 89, 0.12);
      }

      * { box-sizing: border-box; }
      body {
        margin: 0;
        font-family: Inter, "Segoe UI", sans-serif;
        color: var(--text);
        background:
          radial-gradient(circle at top center, rgba(117, 177, 255, 0.16), transparent 34%),
          linear-gradient(180deg, #f7f9fe 0%, #eef3fa 100%);
      }

      a { color: inherit; text-decoration: none; }

      .page {
        max-width: 1460px;
        margin: 0 auto;
        padding: 28px 28px 42px;
      }

      .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        padding: 18px 22px;
        border-radius: 30px;
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.82);
        box-shadow: var(--shadow);
      }

      .brand {
        display: flex;
        align-items: center;
        gap: 16px;
      }

      .brand-mark {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        font-weight: 800;
        font-size: 22px;
        color: white;
        background: linear-gradient(135deg, var(--accent) 0%, #6a7cff 100%);
      }

      .brand-copy strong {
        display: block;
        font-size: 14px;
      }

      .brand-copy span {
        display: block;
        margin-top: 4px;
        color: var(--accent);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.18em;
      }

      .nav {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: flex-end;
      }

      .nav-link {
        padding: 14px 18px;
        border-radius: 18px;
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.72);
        color: #32466e;
        font-weight: 700;
        font-size: 14px;
      }

      .nav-link.is-active {
        background: linear-gradient(135deg, #3ba8ec 0%, #6374ff 100%);
        color: white;
        border-color: transparent;
      }

      .hero {
        margin-top: 22px;
        padding: 28px 28px 24px;
        border-radius: 32px;
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.88);
        box-shadow: var(--shadow);
      }

      .eyebrow {
        margin: 0 0 12px;
        color: var(--accent);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.22em;
        text-transform: uppercase;
      }

      h1 {
        margin: 0;
        max-width: 13ch;
        font-family: Georgia, "Times New Roman", serif;
        font-size: clamp(38px, 4.4vw, 68px);
        line-height: 0.97;
        letter-spacing: -0.042em;
      }

      .intro {
        max-width: 72ch;
        margin: 14px 0 0;
        color: var(--muted);
        font-size: 18px;
        line-height: 1.46;
      }

      .metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-top: 24px;
      }

      .metric-card,
      .panel,
      .audit-card,
      .schema-card,
      .lane-card,
      .subcard {
        border-radius: 24px;
        border: 1px solid var(--line);
        background: rgba(255, 255, 255, 0.78);
      }

      .metric-card {
        min-height: 172px;
        padding: 18px 18px 16px;
      }

      .metric-card h2,
      .panel h2,
      .lane-card h2 {
        margin: 0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: var(--accent);
      }

      .metric-card strong {
        display: block;
        margin-top: 16px;
        font-size: 58px;
        line-height: 0.95;
      }

      .metric-card p {
        margin: 12px 0 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.45;
      }

      .section-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 20px;
        margin-top: 22px;
      }

      .panel {
        padding: 22px;
      }

      .panel p {
        color: var(--muted);
        line-height: 1.55;
      }

      .audit-list,
      .schema-list,
      .lane-list {
        display: grid;
        gap: 16px;
        margin-top: 16px;
      }

      .audit-card,
      .schema-card,
      .lane-card {
        padding: 18px 18px 16px;
      }

      .audit-top,
      .schema-top {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
      }

      .audit-top strong,
      .schema-top strong {
        display: block;
        font-size: 28px;
        line-height: 1;
      }

      .meta {
        margin-top: 8px;
        color: var(--muted);
        font-size: 14px;
      }

      .badge {
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        font-weight: 800;
      }

      .status-breaking {
        color: var(--bad);
        background: rgba(224, 95, 118, 0.12);
      }

      .status-watch {
        color: var(--warn);
        background: rgba(209, 164, 70, 0.14);
      }

      .status-healthy {
        color: var(--good);
        background: rgba(42, 159, 112, 0.12);
      }

      .card-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 16px;
      }

      .subcard {
        padding: 18px;
      }

      .subcard h3 {
        margin: 0;
        font-size: 17px;
      }

      .subcard p,
      .subcard ul {
        color: var(--muted);
        line-height: 1.5;
      }

      .subcard ul {
        margin: 10px 0 0;
        padding-left: 18px;
      }

      .lane-card h3 {
        margin: 14px 0 6px;
        font-size: 26px;
      }

      .lane-meta {
        color: var(--muted);
        display: grid;
        gap: 6px;
        font-size: 14px;
      }

      .footer-note {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 18px;
        color: var(--muted);
        font-size: 14px;
      }

      .footer-note strong {
        color: #1d2a46;
      }

      @media (max-width: 1120px) {
        .metrics,
        .section-grid,
        .card-grid {
          grid-template-columns: 1fr 1fr;
        }

        .topbar {
          flex-direction: column;
          align-items: start;
        }
      }

      @media (max-width: 820px) {
        .page {
          padding: 18px 14px 34px;
        }

        .metrics,
        .section-grid,
        .card-grid {
          grid-template-columns: 1fr;
        }

        .nav {
          width: 100%;
          justify-content: start;
        }
      }
    </style>
  </head>
  <body>
    <div class="page">
      <div class="topbar">
        <div class="brand">
          <div class="brand-mark">WP</div>
          <div class="brand-copy">
            <strong>WordPress Block SEO Governance Auditor</strong>
            <span>Block hierarchy + answer surface control</span>
          </div>
        </div>
        <div class="nav">{$nav}</div>
      </div>

      <section class="hero">
        <p class="eyebrow">WordPress answer-surface governance</p>
        <h1>{$title}</h1>
        <p class="intro">{$intro}</p>

        <div class="metrics">
          <div class="metric-card">
            <h2>Audited pages</h2>
            <strong>{$summary['pageCount']}</strong>
            <p>WordPress page surfaces currently checked for heading order, answer depth, and schema opportunities.</p>
          </div>
          <div class="metric-card">
            <h2>Breaking pages</h2>
            <strong>{$summary['breakingCount']}</strong>
            <p>Pages where block structure is actively weakening answer-engine readability or trust.</p>
          </div>
          <div class="metric-card">
            <h2>Watch lane</h2>
            <strong>{$summary['watchCount']}</strong>
            <p>Pages that are usable but still carrying schema or answer-depth gaps worth fixing next.</p>
          </div>
          <div class="metric-card">
            <h2>Avg answer score</h2>
            <strong>{$summary['averageAnswerScore']}</strong>
            <p>The current average answer-surface quality across the audited block stack.</p>
          </div>
        </div>
      </section>

      {$content}
    </div>
  </body>
</html>
HTML;
}

function render_overview(): string
{
    $service = new AuditService();
    $pages = array_slice($service->pages(), 0, 3);
    $schemaOpportunities = array_slice($service->schemaOpportunities(), 0, 2);
    $summary = $service->summary();

    $pageCards = '';
    foreach ($pages as $page) {
        $blocks = htmlspecialchars(implode(', ', $page['blocks']), ENT_QUOTES);
        $pageCards .= <<<HTML
<article class="audit-card">
  <div class="audit-top">
    <div>
      <strong>{$page['title']}</strong>
      <div class="meta">{$page['owner']} · {$page['slug']} · headings {$page['headingScore']} · answer {$page['answerScore']}</div>
    </div>
    <span class="badge status-{$page['status']}">{$page['status']}</span>
  </div>
  <div class="card-grid">
    <div class="subcard">
      <h3>Lead issue</h3>
      <p>{$page['leadIssue']}</p>
    </div>
    <div class="subcard">
      <h3>Next action</h3>
      <p>{$page['nextAction']}</p>
    </div>
    <div class="subcard">
      <h3>Detected blocks</h3>
      <p>{$blocks}</p>
    </div>
    <div class="subcard">
      <h3>Schema score</h3>
      <p>{$page['schemaScore']}</p>
    </div>
  </div>
</article>
HTML;
    }

    $schemaCards = '';
    foreach ($schemaOpportunities as $item) {
        $schemaCards .= <<<HTML
<article class="schema-card">
  <div class="schema-top">
    <div>
      <strong>{$item['surface']}</strong>
      <div class="meta">{$item['owner']} · {$item['opportunity']}</div>
    </div>
  </div>
  <p>{$item['reason']}</p>
</article>
HTML;
    }

    $content = <<<HTML
<section class="section-grid">
  <div class="panel">
    <h2>Lead recommendation</h2>
    <p>{$summary['leadRecommendation']}</p>
    <div class="audit-list">{$pageCards}</div>
  </div>
  <div class="panel">
    <h2>Schema opportunity lane</h2>
    <p>These are the block-level schema and answer-format opportunities that can improve structured retrieval the fastest.</p>
    <div class="schema-list">{$schemaCards}</div>
    <div class="footer-note">
      <span>Healthy pages: <strong>{$summary['healthyCount']}</strong></span>
      <span>Goal: make the block stack as answer-safe as the copy itself.</span>
    </div>
  </div>
</section>
HTML;

    return render_shell(
        '/',
        'Catch weak WordPress block structure before it weakens SEO and answer surfaces.',
        'This auditor looks at pages the way search and answer engines do: heading order, FAQ readiness, schema opportunities, and whether block composition is helping or hurting retrieval-safe content.',
        $content,
    );
}

function render_block_audit(): string
{
    $service = new AuditService();
    $pages = $service->pages();

    $cards = '';
    foreach ($pages as $page) {
        $blocks = htmlspecialchars(implode(', ', $page['blocks']), ENT_QUOTES);
        $cards .= <<<HTML
<article class="audit-card">
  <div class="audit-top">
    <div>
      <strong>{$page['title']}</strong>
      <div class="meta">{$page['owner']} · {$page['slug']}</div>
    </div>
    <span class="badge status-{$page['status']}">{$page['status']}</span>
  </div>
  <div class="card-grid">
    <div class="subcard">
      <h3>Heading score</h3>
      <p>{$page['headingScore']}</p>
    </div>
    <div class="subcard">
      <h3>Answer score</h3>
      <p>{$page['answerScore']}</p>
    </div>
    <div class="subcard">
      <h3>Lead issue</h3>
      <p>{$page['leadIssue']}</p>
    </div>
    <div class="subcard">
      <h3>Detected blocks</h3>
      <p>{$blocks}</p>
    </div>
    <div class="subcard">
      <h3>Schema score</h3>
      <p>{$page['schemaScore']}</p>
    </div>
    <div class="subcard">
      <h3>Next action</h3>
      <p>{$page['nextAction']}</p>
    </div>
  </div>
</article>
HTML;
    }

    return render_shell(
        '/block-audit',
        'See which WordPress pages are structurally safe and which ones still need block-level repair.',
        'The block audit lane keeps weak headings, shallow FAQs, and over-promotional layouts visible before they become search and answer-engine debt.',
        <<<HTML
<section class="panel" style="margin-top: 22px;">
  <h2>Page audit lane</h2>
  <div class="audit-list">{$cards}</div>
</section>
HTML,
    );
}

function render_schema_opportunities(): string
{
    $service = new AuditService();
    $items = $service->schemaOpportunities();

    $cards = '';
    foreach ($items as $item) {
        $cards .= <<<HTML
<article class="schema-card">
  <div class="schema-top">
    <div>
      <strong>{$item['surface']}</strong>
      <div class="meta">{$item['owner']} · {$item['opportunity']}</div>
    </div>
  </div>
  <p>{$item['reason']}</p>
</article>
HTML;
    }

    return render_shell(
        '/schema-opportunities',
        'Find the schema and FAQ opportunities hidden inside the current block layout.',
        'This lane focuses on block combinations that could be repackaged into stronger FAQ, HowTo, or glossary-style answer surfaces without rewriting everything from scratch.',
        <<<HTML
<section class="section-grid">
  <div class="panel">
    <h2>Opportunity register</h2>
    <div class="schema-list">{$cards}</div>
  </div>
  <div class="panel">
    <h2>Why block-level schema matters</h2>
    <div class="card-grid">
      <article class="subcard">
        <h3>FAQ packaging</h3>
        <p>Good answers often exist on the page already, but block structure leaves them too fragmented for clean FAQ extraction.</p>
      </article>
      <article class="subcard">
        <h3>HowTo separation</h3>
        <p>Instructional content performs better when steps and caution notes are clearly segmented instead of mixed with inspiration copy.</p>
      </article>
      <article class="subcard">
        <h3>Defined terms</h3>
        <p>Glossary-like sizing and materials language can reinforce entity coverage without turning the page into an encyclopedia.</p>
      </article>
      <article class="subcard">
        <h3>Retrieval-safe structure</h3>
        <p>Schema helps most when it is supported by the visible block hierarchy, not used as a patch over messy page anatomy.</p>
      </article>
    </div>
  </div>
</section>
HTML,
    );
}

function render_verification(): string
{
    $service = new AuditService();
    $summary = $service->summary();
    $lanes = $service->verificationLanes();

    $cards = '';
    foreach ($lanes as $lane) {
        $cards .= <<<HTML
<article class="lane-card">
  <h2>{$lane['lane']}</h2>
  <h3>{$lane['score']}</h3>
  <div class="lane-meta">
    <span>{$lane['note']}</span>
  </div>
</article>
HTML;
    }

    return render_shell(
        '/verification',
        'See what the current WordPress block posture proves about answer-surface safety.',
        'The verification view condenses the audit into the signals that matter most before you decide which editorial or template work to prioritize next.',
        <<<HTML
<section class="section-grid">
  <div class="panel">
    <h2>Verification proof</h2>
    <div class="card-grid">
      <article class="subcard">
        <h3>Audited pages</h3>
        <p>{$summary['pageCount']} pages are currently modeled for heading, answer, and schema posture.</p>
      </article>
      <article class="subcard">
        <h3>Breaking surfaces</h3>
        <p>{$summary['breakingCount']} pages should be repaired before more AI-facing traffic leans on them.</p>
      </article>
      <article class="subcard">
        <h3>Healthy block stacks</h3>
        <p>{$summary['healthyCount']} pages already show the kind of concise, question-safe structure you want to repeat.</p>
      </article>
      <article class="subcard">
        <h3>Average answer score</h3>
        <p>{$summary['averageAnswerScore']} suggests the stack is workable, but still uneven where promotional content dominates.</p>
      </article>
    </div>
  </div>
  <div class="panel">
    <h2>Audit lanes</h2>
    <div class="lane-list">{$cards}</div>
  </div>
</section>
HTML,
    );
}

function render_docs(): string
{
    return render_shell(
        '/docs',
        'Understand the routes, payloads, and plugin-facing surfaces modeled in the PHP auditor.',
        'The app ships both browser routes and JSON payloads so the audit can work as an operator surface and a WordPress-oriented governance data source.',
        <<<HTML
<section class="section-grid">
  <div class="panel">
    <h2>HTML routes</h2>
    <div class="card-grid">
      <article class="subcard"><h3>/</h3><p>Overview of page-level block governance posture.</p></article>
      <article class="subcard"><h3>/block-audit</h3><p>Page-by-page block audit and status lane.</p></article>
      <article class="subcard"><h3>/schema-opportunities</h3><p>FAQ, HowTo, and glossary opportunity register.</p></article>
      <article class="subcard"><h3>/verification</h3><p>Current proof posture across the audit lanes.</p></article>
      <article class="subcard"><h3>/docs</h3><p>Route and payload reference.</p></article>
    </div>
  </div>
  <div class="panel">
    <h2>JSON routes</h2>
    <div class="card-grid">
      <article class="subcard"><h3>/api/summary</h3><p>High-level audit counts and lead recommendation.</p></article>
      <article class="subcard"><h3>/api/pages</h3><p>Sorted page audit records with block and score data.</p></article>
      <article class="subcard"><h3>/api/schema-opportunities</h3><p>Schema register and owner lanes.</p></article>
      <article class="subcard"><h3>/api/verification</h3><p>Verification lane scores and notes.</p></article>
      <article class="subcard"><h3>/api/sample</h3><p>Full sample payload in one response.</p></article>
    </div>
  </div>
</section>
HTML,
    );
}
