<?php

use App\Livewire\Reports\Feed;
use App\Models\Report;
use Livewire\Livewire;

it('shows only published reports in the public feed', function () {
    $published = Report::factory()->published()->create();
    $received = Report::factory()->received()->create();

    Livewire::test(Feed::class)
        ->assertSee($published->address)
        ->assertDontSee($received->address);
});

it('returns 404 for an unpublished public detail', function () {
    $report = Report::factory()->restricted()->create();

    $this->get(route('reports.show', $report))->assertNotFound();
});

it('escapes user content on the public detail', function () {
    $report = Report::factory()->published()->create([
        'description' => '<script>alert("xss")</script> descarte irregular',
    ]);

    $this->get(route('reports.show', $report))
        ->assertSee('&lt;script&gt;', escape: false)
        ->assertDontSee('<script>alert("xss")</script>', escape: false);
});

it('uses the same Mary layout on report screens but not on the welcome page', function () {
    $report = Report::factory()->published()->create();

    $reportPages = [
        route('management.dashboard'),
        route('reports.index'),
        route('reports.show', $report),
        route('management.reports.index'),
        route('management.reports.create'),
        route('management.reports.edit', $report),
    ];

    foreach ($reportPages as $page) {
        $this->get($page)
            ->assertOk()
            ->assertSee('main-drawer', escape: false)
            ->assertSee('theme-controller', escape: false);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('main-drawer', escape: false);
});

it('isolates the landing navigation script across Livewire visits', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('(() => {', escape: false)
        ->assertSee("document.body.addEventListener('keydown'", escape: false)
        ->assertDontSee("<script>\n        const navigationToggle", escape: false);
});
