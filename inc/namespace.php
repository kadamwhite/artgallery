<?php

namespace ArtGallery;

use ArtGallery\Post_Types;
use ArtGallery\Taxonomies;

function setup() {
	Taxonomies\setup();
	Post_Types\setup();
}
