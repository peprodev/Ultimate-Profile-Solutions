(function () {
    
    function get_between(str, first_character, last_character) {
        new_str = str.match(first_character + "(.*)" + last_character)[1].trim()
        return (new_str)
    }

    tinymce.create('tinymce.plugins.inlinenav', {
        init: function (ed, url) {
            ed.addButton('inlinenav_btn', {
                title: "Add post navigation item",
                cmd: 'inlinenav_command',
                text: "Add Navigation",
            });
            ed.addCommand('inlinenav_command', function () {
                var selected_text = ed.selection.getContent();     
                var return_text = `[post-nav menu_label="${selected_text}" anchor_id="" heading="${selected_text}"]`;
                ed.execCommand('mceInsertContent', 0, return_text);
            });
        },
        getInfo: function () {
            return {
                longname: "Pepro Post Inline Nav",
                author: "Amirhosseinhpv",
                version: "1.0.0"
            };
        }
    });
    tinymce.PluginManager.add('inlinenav', tinymce.plugins.inlinenav);
})();