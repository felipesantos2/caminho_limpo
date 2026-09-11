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
