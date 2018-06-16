$(document).ready(function () {
    CKEDITOR.config.defaultLanguage = SunlightVars.pluginWysiwyg.systemLang;
    CKEDITOR.config.height = 400;
    CKEDITOR.config.ignoreEmptyParagraph = true;
    CKEDITOR.config.entities = false;
    CKEDITOR.config.preset = 'full';

    $('.editor').each(function () {
        CKEDITOR.replace(this);
    });
});