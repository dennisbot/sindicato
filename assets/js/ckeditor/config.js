/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	//http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
	config.forcePasteAsPlainText = true;
	config.toolbar = 'Miconfig';
	config.language = 'es';
	config.skin = 'kama';
	config.uiColor = '#D3D3D3';
	config.width = '500px';
	config.height = '150';
	config.toolbar_Miconfig = [['Bold', '-', 'Italic', '-', 'Underline', '-', 'Strike', '-', 'NumberedList', '-', 'BulletedList'], ['RemoveFormat', 'Image', 'HorizontalRule', 'SpecialChar'], ['PasteText', 'PasteFromWord'], ['Preview', 'Maximize', 'ShowBlocks']];
	config.toolbar_Miconfig2 = [['Bold', '-', 'Italic', '-', 'Underline', '-', 'Strike', '-', 'NumberedList', '-', 'BulletedList']];
	
};