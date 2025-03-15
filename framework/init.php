<?php 

//  add custom postype 

$custom_postype = new \Dt_custom_postype\Dt_CustomPosttype;
$taxonomy = new \Dt_custom_postype\Dt_Taxonomies;

$custom_postype->dt_postype('portfolio', 'Portfolio', 'Portfolio', array('title', 'editor', 'author', 'thumbnail', 'excerpt'));
$taxonomy->dt_taxonomy( 'Type', 'Type', 'Types', 'portfolio');


$custom_postype->dt_postype('Footer', 'Footer', 'Footer', array('title', 'editor', 'author', 'thumbnail', 'excerpt'));