<?php

namespace ArtGallery\Utilities;

const PLUGIN_NAMESPACE = 'artgallery';

function namespaced_block( $block_name ) {
	return PLUGIN_NAMESPACE . '/' . $block_name;
}
