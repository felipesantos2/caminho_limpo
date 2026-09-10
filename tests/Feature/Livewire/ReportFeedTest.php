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

it('uses the same Mary theme on every screen but only the management panel has the sidebar drawer', function () {
    $report = Report::factory()->published()->create();

    $panelPages = [
        route('management.dashboard'),
        route('reports.index'),
        route('reports.show', $report),
        route('management.reports.index'),
        route('management.reports.create'),
        route('management.reports.show', $report),
        route('management.reports.edit', $report),
    ];

    foreach ($panelPages as $page) {
        $this->get($page)
            ->assertOk()
            ->assertSee('main-drawer', escape: false)
            ->assertSee('theme-controller', escape: false);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('theme-controller', escape: false)
        ->assertDontSee('main-drawer', escape: false);
});
