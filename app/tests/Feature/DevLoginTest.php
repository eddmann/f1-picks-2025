<?php

it('returns 404 for dev login route outside development environment', function () {
    // Arrange

    // Act
    $response = $this->get('/dev/login/test@example.com');

    // Assert
    $response->assertNotFound();
});
