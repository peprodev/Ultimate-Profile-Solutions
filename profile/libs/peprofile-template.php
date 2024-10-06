<?php
/**
* Template Name: PeproDev Ultimate Profile Solutions — Profile
* @author Pepro.Dev
*/
# @Last modified by:   Amirhosseinhpv
# @Last modified time: 2021/08/28 20:28:30
echo apply_filters( 'the_content', get_the_content("","",get_queried_object()->ID ) );
