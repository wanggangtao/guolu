/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.image_previewText=' ';
    //config.filebrowserImageUploadUrl= "../web/poster_upload.php?type=img";
   // config.filebrowserUploadUrl = '../web/poster_upload.php?type=file';
    config.width = 600; //宽度
    config.height = 400; //高度
    config.paddingLeft = 92;
    config.paddingRight = 91;
    config.toolbar = 'Common';
	config.format_tags = 'p;h1;h2;h3;div';
	config.allowedContent = true;
	config.extraAllowedContent = '*(*)';
	//config.removeDialogTabs = 'image:advanced;image:Upload;image:Link';
    config.codeSnippet_theme = 'default';
    //工具条自定义
    config.toolbar_Basic = [['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'About']];
	
	//工具条--最常用
	config.toolbar_Common =[
        ['Paste', 'PasteText', 'PasteFromWord','-', 'Undo', 'Redo'],['Bold','Italic','Underline'],['NumberedList','BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight','-','Outdent','Indent'],['TextColor','BGColor','Format'],['Link','Unlink','Anchor'],['Image', 'Table', 'SpecialChar'],['Source'],['CodeSnippet'],['Maximize']
    ];
  /*
	config.toolbar_Full =
        [
            ['Source'],['CodeSnippet'],['Link','Unlink','Anchor'],['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
            ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
            '/',
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['TextColor','BGColor'],['Styles','Format','FontSize'],['Bold','Italic','Underline','Strike','-'],
            ['Maximize', 'ShowBlocks','-']
        ];
	config.toolbar_Meeting =
        [
            ['Source'],['CodeSnippet'],['Link','Unlink','Anchor'],['Image', 'Table', 'HorizontalRule',  'SpecialChar'],
            ['NumberedList','BulletedList','-','Outdent','Indent'],           
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['TextColor','BGColor'],['Bold','Italic','Underline','Strike','-'],
            ['Maximize', 'ShowBlocks','-']
        ];
		
	config.toolbar_Doc =
        [
            ['Source'],['CodeSnippet'],['Link','Unlink','Anchor'],['Image', 'Table', 'HorizontalRule', 'SpecialChar'],
            ['NumberedList','BulletedList','-','Outdent','Indent'],          
            ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            ['TextColor','BGColor'],['Styles','Format','FontSize'],['Bold','Italic','Underline','Strike','-'],
            ['Maximize', 'ShowBlocks','-']
        ];*/
};


