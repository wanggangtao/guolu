/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

    config.extraPlugins += (config.extraPlugins ? ',lineheight' : 'lineheight');

    //配置文件的参数可以访问http://docs.ckeditor.com/#!/api/CKEDITOR.config
    //这里是通用配置。可以在加载的时候进行自定义配置

    config.language = 'zh-cn';
    //config.uiColor = '#F7B42C';//颜色
    config.width = '100%';
    config.height = 300;
    //config.toolbarCanCollapse = true;//工具条能否收起
    //工具条--最简单
    config.toolbar_Basic = [
        ['Paste', 'PasteText', 'PasteFromWord','-', 'Undo', 'Redo'],['Bold','Italic','Underline'],['NumberedList', 'BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight'],['Link', 'Unlink']
    ];

    config.toolbar_Common = [
        ['Source','-','Save','NewPage','Preview','-','Templates'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink','Anchor'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
        '/',
        ['Styles','Format','Font','FontSize','lineheight'],
        ['TextColor','BGColor']
    ];

    //定义上传处理文件//留空表示禁止上传
    config.filebrowserUploadUrl = 'ckeditor_upload.php?type=file';
    config.filebrowserImageUploadUrl= "ckeditor_upload.php?type=img";

    // Set the most common block elements.显示在toolbar中“格式”下的样式
    config.format_tags = 'p;h1;h2;h3';

    // Simplify the dialog windows.简化弹出框
    config.removeDialogTabs = 'image:advanced;link:advanced';

    //是否强制粘贴为纯文本格式
    config.forcePasteAsPlainText = false;

    config.font_names = '宋体/SimSun;新宋体/NSimSun;仿宋/FangSong;楷体/KaiTi;仿宋_GB2312/FangSong_GB2312;'+
        '楷体_GB2312/KaiTi_GB2312;黑体/SimHei;华文细黑/STXihei;华文楷体/STKaiti;华文宋体/STSong;华文中宋/STZhongsong;'+
        '华文仿宋/STFangsong;华文彩云/STCaiyun;华文琥珀/STHupo;华文隶书/STLiti;华文行楷/STXingkai;华文新魏/STXinwei;'+
        '方正舒体/FZShuTi;方正姚体/FZYaoti;细明体/MingLiU;新细明体/PMingLiU;微软雅黑/Microsoft YaHei;微软正黑/Microsoft JhengHei;'+
        'Arial Black/Arial Black;'+ config.font_names;
};