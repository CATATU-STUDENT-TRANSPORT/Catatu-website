<?php

it('shows the home page with hero copy', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('CATATU')
        ->assertSee('Direct rides from your area to campus');
});

it('shows about, how-it-works and partner pages', function () {
    $this->get('/about')->assertOk()->assertSee('About CATATU');
    $this->get('/how-it-works')->assertOk()->assertSee('How it works');
    $this->get('/partner')->assertOk()->assertSee('Partner with CATATU');
});

it('accepts a partner application', function () {
    $this->post('/partner', [
        'organization' => 'Strathmore University',
        'contact_name' => 'Esther K.',
        'email' => 'esther@strathmore.edu',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');
});
