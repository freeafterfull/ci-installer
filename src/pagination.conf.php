<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Customizing the Pagination
 */
$config['num_link'] = 5;
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = FALSE;
$config['reuse_query_string'] = TRUE;
$config['prefix'] = '';
$config['suffix'] = '/';
$config['use_global_url_suffix'] = FALSE;

/**
 * Adding Enclosing Markup
 */
$config['full_tag_open'] = '<ul>';
$config['full_tag_close'] = '</ul>';

/**
 * Customizing the First Link
 */
$config['first_link'] = 'First';
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
$config['first_url'] = '';

/**
 * Customizing the Last Link
 */
$config['last_link'] = 'Last';
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';

/**
 * Customizing the “Next” Link
 */
$config['next_link'] = 'Next';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';

/**
 * Customizing the “Previous” Link
 */
$config['prev_link'] = 'Previous';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';

/**
 * Customizing the “Current Page” Link
 */
$config['cur_tag_open'] = '<li>';
$config['cur_tag_close'] = '</li>';

/**
 * Customizing the “Digit” Link
 */
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';

/**
 * Hiding the Pages
 */
$config['display_pages'] = TRUE;

/**
 * Adding attributes to anchors
 */
$config['attributes'] = ['class' => 'page-link'];

/**
 * Disabling the “rel” attribute
 */
$config['attributes']['rel'] = FALSE;
