<?php

it('renders the public home page with the main calls to action', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Um caminho simples para uma cidade mais limpa.')
        ->assertSee('Como funciona')
        ->assertSee('Para a administração municipal')
        ->assertSee(route('management.reports.create'), escape: false)
        ->assertSee(route('reports.index'), escape: false);
});
