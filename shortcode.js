jQuery(document).ready(function($) {

    tinymce.create('tinymce.plugins.beerRecommender_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('beerRecommender_insert_shortcode', function() {               
                        content =  '[beerrecommender]';
                    tinymce.execCommand('mceInsertContent', false, content);
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('beerRecommender_button', {title : 'Beer', cmd : 'beerRecommender_insert_shortcode', image: url + '/beerglass.png' });
			
        },   
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('beerRecommender_button', tinymce.plugins.beerRecommender_plugin);
});