<?php

namespace ArtGallery;

use ArtGallery\Blocks;
use ArtGallery\Meta;
use ArtGallery\Post_Types;
use ArtGallery\Scripts;
use ArtGallery\Taxonomies;

function setup() {
	Taxonomies\setup();
	Post_Types\setup();
	Meta\setup();
	Blocks\setup();
	Scripts\setup();
}
