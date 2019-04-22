<?php

namespace ArtGallery;

use ArtGallery\Blocks;
use ArtGallery\Post_Types;
use ArtGallery\Scripts;
use ArtGallery\Taxonomies;

function setup() {
	Taxonomies\setup();
	Post_Types\setup();
	Blocks\setup();
	Scripts\setup();
}
