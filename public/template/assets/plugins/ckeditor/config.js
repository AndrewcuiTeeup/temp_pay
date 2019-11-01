/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */


CKEDITOR.editorConfig = function( config ) {
    config.language = 'en';
    config.toolbarGroups = [
        { name: 'document', groups: [ 'doctools', 'mode', 'document' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'selection', 'find', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
        { name: 'basicstyles', groups: [ 'cleanup', 'basicstyles' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] },
        { name: 'tools', groups: [ 'tools' ] }
    ];
    // Construct path to file upload route
// Useful if your dev and prod URLs are different
    var path = CKEDITOR.basePath.split('/');
    path[ path.length-2 ] = 'upload_image';
    config.filebrowserUploadUrl = path.join('/').replace(/\/+$/, '');

// Add plugin
    config.extraPlugins = 'filebrowser';
    config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,HiddenField,CopyFormatting,RemoveFormat,Strike,Subscript,Superscript,Outdent,Indent,Blockquote,CreateDiv,Flash,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,About,ShowBlocks,ImageButton,Anchor,HorizontalRule,Maximize,Language,BidiRtl,BidiLtr,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock';
   };